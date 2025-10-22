<?php

declare(strict_types=1);

namespace Challenge02\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use SharedBackend\Core\Logger;
use SharedBackend\Core\Database;
use SharedBackend\Core\Cache;
use SharedBackend\Core\EventDispatcher;
use Challenge02\Models\User;
use Challenge02\Models\Session;
use Challenge02\Models\RefreshToken;
use Challenge02\Exceptions\AuthException;
use Challenge02\Exceptions\ValidationException;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key as CryptoKey;
use Ramsey\Uuid\Uuid;

/**
 * Advanced authentication service with multiple auth methods
 */
class AuthService
{
    private Logger $logger;
    private Database $database;
    private Cache $cache;
    private EventDispatcher $eventDispatcher;
    private string $jwtSecret;
    private string $jwtAlgorithm;
    private int $jwtExpiration;
    private int $refreshTokenExpiration;
    private CryptoKey $encryptionKey;

    public function __construct(
        Logger $logger,
        Database $database,
        Cache $cache,
        EventDispatcher $eventDispatcher,
        string $jwtSecret,
        string $jwtAlgorithm = 'HS256',
        int $jwtExpiration = 3600,
        int $refreshTokenExpiration = 2592000 // 30 days
    ) {
        $this->logger = $logger;
        $this->database = $database;
        $this->cache = $cache;
        $this->eventDispatcher = $eventDispatcher;
        $this->jwtSecret = $jwtSecret;
        $this->jwtAlgorithm = $jwtAlgorithm;
        $this->jwtExpiration = $jwtExpiration;
        $this->refreshTokenExpiration = $refreshTokenExpiration;
        
        // Initialize encryption key
        $this->encryptionKey = CryptoKey::createNewRandomKey();
    }

    public function register(array $data): array
    {
        $this->validateRegistrationData($data);

        // Check if user already exists
        if ($this->userExists($data['email'])) {
            throw new AuthException('User with this email already exists');
        }

        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_ARGON2ID);

        // Create user
        $userId = Uuid::uuid4()->toString();
        $user = new User([
            'id' => $userId,
            'email' => $data['email'],
            'password' => $hashedPassword,
            'first_name' => $data['first_name'] ?? '',
            'last_name' => $data['last_name'] ?? '',
            'phone' => $data['phone'] ?? null,
            'is_active' => true,
            'email_verified_at' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->saveUser($user);

        // Send verification email
        $this->sendVerificationEmail($user);

        // Dispatch event
        $this->eventDispatcher->dispatch(new UserRegisteredEvent($user));

        $this->logger->info('User registered successfully', [
            'user_id' => $userId,
            'email' => $data['email']
        ]);

        return [
            'user' => $user->toArray(),
            'message' => 'Registration successful. Please verify your email.'
        ];
    }

    public function login(array $credentials): array
    {
        $this->validateLoginCredentials($credentials);

        $user = $this->findUserByEmail($credentials['email']);
        if (!$user || !$user->is_active) {
            throw new AuthException('Invalid credentials');
        }

        if (!password_verify($credentials['password'], $user->password)) {
            $this->handleFailedLogin($user);
            throw new AuthException('Invalid credentials');
        }

        // Check if email is verified (if required)
        if (!$user->email_verified_at && $this->requiresEmailVerification()) {
            throw new AuthException('Please verify your email before logging in');
        }

        // Generate tokens
        $tokens = $this->generateTokens($user);
        
        // Create session
        $session = $this->createSession($user, $credentials['remember_me'] ?? false);

        // Update last login
        $this->updateLastLogin($user);

        // Dispatch event
        $this->eventDispatcher->dispatch(new UserLoggedInEvent($user, $session));

        $this->logger->info('User logged in successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);

        return [
            'user' => $user->toArray(),
            'tokens' => $tokens,
            'session' => $session->toArray()
        ];
    }

    public function logout(string $token, string $sessionId = null): void
    {
        try {
            $payload = $this->validateToken($token);
            $userId = $payload['sub'];

            // Revoke refresh token
            $this->revokeRefreshToken($payload['jti'] ?? null);

            // End session
            if ($sessionId) {
                $this->endSession($sessionId, $userId);
            }

            // Add token to blacklist
            $this->blacklistToken($token);

            // Dispatch event
            $this->eventDispatcher->dispatch(new UserLoggedOutEvent($userId));

            $this->logger->info('User logged out successfully', [
                'user_id' => $userId,
                'session_id' => $sessionId
            ]);
        } catch (\Exception $e) {
            $this->logger->warning('Logout failed', [
                'error' => $e->getMessage(),
                'token' => substr($token, 0, 20) . '...'
            ]);
            throw new AuthException('Logout failed');
        }
    }

    public function refreshToken(string $refreshToken): array
    {
        $tokenData = $this->validateRefreshToken($refreshToken);
        
        $user = $this->findUserById($tokenData['user_id']);
        if (!$user || !$user->is_active) {
            throw new AuthException('Invalid refresh token');
        }

        // Generate new tokens
        $tokens = $this->generateTokens($user);
        
        // Revoke old refresh token
        $this->revokeRefreshToken($tokenData['token_id']);

        $this->logger->info('Token refreshed successfully', [
            'user_id' => $user->id
        ]);

        return $tokens;
    }

    public function verifyEmail(string $token): void
    {
        $verificationData = $this->validateVerificationToken($token);
        
        $user = $this->findUserById($verificationData['user_id']);
        if (!$user) {
            throw new AuthException('Invalid verification token');
        }

        if ($user->email_verified_at) {
            throw new AuthException('Email already verified');
        }

        $this->markEmailAsVerified($user);

        // Dispatch event
        $this->eventDispatcher->dispatch(new EmailVerifiedEvent($user));

        $this->logger->info('Email verified successfully', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);
    }

    public function forgotPassword(string $email): void
    {
        $user = $this->findUserByEmail($email);
        if (!$user) {
            // Don't reveal if user exists
            return;
        }

        $resetToken = $this->generatePasswordResetToken($user);
        $this->sendPasswordResetEmail($user, $resetToken);

        $this->logger->info('Password reset requested', [
            'user_id' => $user->id,
            'email' => $email
        ]);
    }

    public function resetPassword(string $token, string $newPassword): void
    {
        $resetData = $this->validatePasswordResetToken($token);
        
        $user = $this->findUserById($resetData['user_id']);
        if (!$user) {
            throw new AuthException('Invalid reset token');
        }

        $this->updatePassword($user, $newPassword);
        $this->invalidatePasswordResetToken($token);

        // Dispatch event
        $this->eventDispatcher->dispatch(new PasswordResetEvent($user));

        $this->logger->info('Password reset successfully', [
            'user_id' => $user->id
        ]);
    }

    public function validateToken(string $token): array
    {
        try {
            $payload = JWT::decode($token, new Key($this->jwtSecret, $this->jwtAlgorithm));
            
            // Check blacklist
            if ($this->isTokenBlacklisted($token)) {
                throw new AuthException('Token has been revoked');
            }

            return (array)$payload;
        } catch (ExpiredException $e) {
            throw new AuthException('Token has expired');
        } catch (SignatureInvalidException $e) {
            throw new AuthException('Invalid token signature');
        } catch (\Exception $e) {
            throw new AuthException('Invalid token');
        }
    }

    public function getUserFromToken(string $token): User
    {
        $payload = $this->validateToken($token);
        $user = $this->findUserById($payload['sub']);
        
        if (!$user) {
            throw new AuthException('User not found');
        }

        return $user;
    }

    private function validateRegistrationData(array $data): void
    {
        $required = ['email', 'password'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new ValidationException("Field '{$field}' is required");
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('Invalid email format');
        }

        if (strlen($data['password']) < 8) {
            throw new ValidationException('Password must be at least 8 characters long');
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', $data['password'])) {
            throw new ValidationException('Password must contain uppercase, lowercase, and number');
        }
    }

    private function validateLoginCredentials(array $credentials): void
    {
        if (empty($credentials['email']) || empty($credentials['password'])) {
            throw new ValidationException('Email and password are required');
        }

        if (!filter_var($credentials['email'], FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('Invalid email format');
        }
    }

    private function userExists(string $email): bool
    {
        $result = $this->database->executeQuery(
            'SELECT id FROM users WHERE email = ?',
            [$email]
        );
        
        return $result->rowCount() > 0;
    }

    private function findUserByEmail(string $email): ?User
    {
        $result = $this->database->executeQuery(
            'SELECT * FROM users WHERE email = ?',
            [$email]
        );

        $data = $result->fetchAssociative();
        return $data ? new User($data) : null;
    }

    private function findUserById(string $id): ?User
    {
        $result = $this->database->executeQuery(
            'SELECT * FROM users WHERE id = ?',
            [$id]
        );

        $data = $result->fetchAssociative();
        return $data ? new User($data) : null;
    }

    private function saveUser(User $user): void
    {
        $this->database->executeStatement(
            'INSERT INTO users (id, email, password, first_name, last_name, phone, is_active, email_verified_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $user->id,
                $user->email,
                $user->password,
                $user->first_name,
                $user->last_name,
                $user->phone,
                $user->is_active,
                $user->email_verified_at,
                $user->created_at,
                $user->updated_at
            ]
        );
    }

    private function generateTokens(User $user): array
    {
        $now = time();
        $jti = Uuid::uuid4()->toString();
        
        $payload = [
            'iss' => 'challenge-02-auth',
            'sub' => $user->id,
            'aud' => 'challenge-02-api',
            'iat' => $now,
            'exp' => $now + $this->jwtExpiration,
            'jti' => $jti,
            'email' => $user->email,
            'roles' => $user->roles ?? []
        ];

        $accessToken = JWT::encode($payload, $this->jwtSecret, $this->jwtAlgorithm);
        
        // Create refresh token
        $refreshToken = $this->createRefreshToken($user, $jti);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'expires_in' => $this->jwtExpiration
        ];
    }

    private function createRefreshToken(User $user, string $jti): string
    {
        $tokenId = Uuid::uuid4()->toString();
        $token = base64_encode(random_bytes(32));
        
        $refreshToken = new RefreshToken([
            'id' => $tokenId,
            'user_id' => $user->id,
            'jti' => $jti,
            'token' => $this->encryptToken($token),
            'expires_at' => date('Y-m-d H:i:s', time() + $this->refreshTokenExpiration),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->saveRefreshToken($refreshToken);

        return $token;
    }

    private function createSession(User $user, bool $rememberMe): Session
    {
        $sessionId = Uuid::uuid4()->toString();
        $expiresAt = $rememberMe 
            ? date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60)) // 30 days
            : date('Y-m-d H:i:s', time() + (24 * 60 * 60)); // 24 hours

        $session = new Session([
            'id' => $sessionId,
            'user_id' => $user->id,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'expires_at' => $expiresAt,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->saveSession($session);

        return $session;
    }

    private function updateLastLogin(User $user): void
    {
        $this->database->executeStatement(
            'UPDATE users SET last_login_at = ?, updated_at = ? WHERE id = ?',
            [date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $user->id]
        );
    }

    private function handleFailedLogin(User $user): void
    {
        // Increment failed login attempts
        $this->database->executeStatement(
            'UPDATE users SET failed_login_attempts = failed_login_attempts + 1, updated_at = ? WHERE id = ?',
            [date('Y-m-d H:i:s'), $user->id]
        );

        // Lock account after 5 failed attempts
        $this->database->executeStatement(
            'UPDATE users SET is_locked = 1, locked_until = ? WHERE id = ? AND failed_login_attempts >= 5',
            [date('Y-m-d H:i:s', time() + (15 * 60)), $user->id] // Lock for 15 minutes
        );
    }

    private function revokeRefreshToken(?string $jti): void
    {
        if ($jti) {
            $this->database->executeStatement(
                'DELETE FROM refresh_tokens WHERE jti = ?',
                [$jti]
            );
        }
    }

    private function endSession(string $sessionId, string $userId): void
    {
        $this->database->executeStatement(
            'UPDATE sessions SET ended_at = ? WHERE id = ? AND user_id = ?',
            [date('Y-m-d H:i:s'), $sessionId, $userId]
        );
    }

    private function blacklistToken(string $token): void
    {
        $key = 'blacklisted_token:' . hash('sha256', $token);
        $this->cache->set($key, true, $this->jwtExpiration);
    }

    private function isTokenBlacklisted(string $token): bool
    {
        $key = 'blacklisted_token:' . hash('sha256', $token);
        return $this->cache->has($key);
    }

    private function validateRefreshToken(string $token): array
    {
        $result = $this->database->executeQuery(
            'SELECT * FROM refresh_tokens WHERE token = ? AND expires_at > NOW()',
            [$this->encryptToken($token)]
        );

        $data = $result->fetchAssociative();
        if (!$data) {
            throw new AuthException('Invalid or expired refresh token');
        }

        return $data;
    }

    private function validateVerificationToken(string $token): array
    {
        $key = 'email_verification:' . $token;
        $data = $this->cache->get($key);
        
        if (!$data) {
            throw new AuthException('Invalid or expired verification token');
        }

        $this->cache->delete($key);
        return $data;
    }

    private function validatePasswordResetToken(string $token): array
    {
        $key = 'password_reset:' . $token;
        $data = $this->cache->get($key);
        
        if (!$data) {
            throw new AuthException('Invalid or expired reset token');
        }

        return $data;
    }

    private function generatePasswordResetToken(User $user): string
    {
        $token = base64_encode(random_bytes(32));
        $data = [
            'user_id' => $user->id,
            'email' => $user->email,
            'created_at' => time()
        ];

        $this->cache->set('password_reset:' . $token, $data, 3600); // 1 hour
        return $token;
    }

    private function generateVerificationToken(User $user): string
    {
        $token = base64_encode(random_bytes(32));
        $data = [
            'user_id' => $user->id,
            'email' => $user->email,
            'created_at' => time()
        ];

        $this->cache->set('email_verification:' . $token, $data, 86400); // 24 hours
        return $token;
    }

    private function markEmailAsVerified(User $user): void
    {
        $this->database->executeStatement(
            'UPDATE users SET email_verified_at = ?, updated_at = ? WHERE id = ?',
            [date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $user->id]
        );
    }

    private function updatePassword(User $user, string $newPassword): void
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_ARGON2ID);
        
        $this->database->executeStatement(
            'UPDATE users SET password = ?, updated_at = ? WHERE id = ?',
            [$hashedPassword, date('Y-m-d H:i:s'), $user->id]
        );
    }

    private function invalidatePasswordResetToken(string $token): void
    {
        $this->cache->delete('password_reset:' . $token);
    }

    private function sendVerificationEmail(User $user): void
    {
        $token = $this->generateVerificationToken($user);
        $verificationUrl = $this->getVerificationUrl($token);
        
        // Send email (implement with PHPMailer)
        // $this->mailer->sendVerificationEmail($user->email, $verificationUrl);
    }

    private function sendPasswordResetEmail(User $user, string $token): void
    {
        $resetUrl = $this->getPasswordResetUrl($token);
        
        // Send email (implement with PHPMailer)
        // $this->mailer->sendPasswordResetEmail($user->email, $resetUrl);
    }

    private function encryptToken(string $token): string
    {
        return Crypto::encrypt($token, $this->encryptionKey);
    }

    private function decryptToken(string $encryptedToken): string
    {
        return Crypto::decrypt($encryptedToken, $this->encryptionKey);
    }

    private function saveRefreshToken(RefreshToken $refreshToken): void
    {
        $this->database->executeStatement(
            'INSERT INTO refresh_tokens (id, user_id, jti, token, expires_at, created_at) VALUES (?, ?, ?, ?, ?, ?)',
            [
                $refreshToken->id,
                $refreshToken->user_id,
                $refreshToken->jti,
                $refreshToken->token,
                $refreshToken->expires_at,
                $refreshToken->created_at
            ]
        );
    }

    private function saveSession(Session $session): void
    {
        $this->database->executeStatement(
            'INSERT INTO sessions (id, user_id, ip_address, user_agent, expires_at, created_at) VALUES (?, ?, ?, ?, ?, ?)',
            [
                $session->id,
                $session->user_id,
                $session->ip_address,
                $session->user_agent,
                $session->expires_at,
                $session->created_at
            ]
        );
    }

    private function requiresEmailVerification(): bool
    {
        // Check configuration
        return true; // Default to requiring verification
    }

    private function getVerificationUrl(string $token): string
    {
        return "https://your-app.com/verify-email?token={$token}";
    }

    private function getPasswordResetUrl(string $token): string
    {
        return "https://your-app.com/reset-password?token={$token}";
    }
}
