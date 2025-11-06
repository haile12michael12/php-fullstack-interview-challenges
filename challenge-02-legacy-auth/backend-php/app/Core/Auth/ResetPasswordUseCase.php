<?php

namespace Challenge02\Core\Auth;

use Challenge02\Contracts\Repositories\UserRepositoryInterface;
use Challenge02\Contracts\Auth\PasswordHasherInterface;
use Challenge02\Contracts\Services\MailServiceInterface;
use Challenge02\Domain\User;

class ResetPasswordUseCase
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

    public function requestReset(string $email): void
    {
        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            // Don't reveal if user exists
            return;
        }

        $resetToken = base64_encode(random_bytes(32));
        $resetUrl = "https://your-app.com/reset-password?token={$resetToken}";
        $this->mailService->sendPasswordResetEmail($user->email, $resetUrl);
    }

    public function execute(string $token, string $newPassword): bool
    {
        // Validate password strength
        if (strlen($newPassword) < 8) {
            throw new \Exception('Password must be at least 8 characters long');
        }

        // In a real implementation, you would validate the token
        // and find the associated user, then update their password
        // For now, we'll just return true to indicate success
        return true;
    }
}