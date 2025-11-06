<?php

namespace Challenge02\Core\Auth;

use Challenge02\Contracts\Repositories\UserRepositoryInterface;
use Challenge02\Contracts\Auth\PasswordHasherInterface;
use Challenge02\Contracts\Services\MailServiceInterface;
use Challenge02\Domain\User;

class RegisterUserUseCase
{
    private UserRepositoryInterface $userRepository;
    private PasswordHasherInterface $passwordHasher;
    private MailServiceInterface $mailService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PasswordHasherInterface $passwordHasher,
        MailServiceInterface $mailService
    ) {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->mailService = $mailService;
    }

    public function execute(array $data): array
    {
        // Validate input data
        $this->validateInput($data);

        // Check if user already exists
        if ($this->userRepository->exists($data['email'])) {
            throw new \Exception('User with this email already exists');
        }

        // Hash password
        $hashedPassword = $this->passwordHasher->hash($data['password']);

        // Create user entity
        $user = new User([
            'id' => uniqid(),
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

        // Save user
        $this->userRepository->save($user);

        // Send verification email
        $verificationToken = base64_encode(random_bytes(32));
        $verificationUrl = "https://your-app.com/verify-email?token={$verificationToken}";
        $this->mailService->sendVerificationEmail($user->email, $verificationUrl);

        return [
            'user' => $user->toArray(),
            'message' => 'Registration successful. Please verify your email.'
        ];
    }

    private function validateInput(array $data): void
    {
        $required = ['email', 'password'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("Field '{$field}' is required");
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Invalid email format');
        }

        if (strlen($data['password']) < 8) {
            throw new \Exception('Password must be at least 8 characters long');
        }
    }
}