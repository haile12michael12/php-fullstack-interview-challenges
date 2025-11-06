<?php

namespace Challenge02\Contracts\Services;

interface MailServiceInterface
{
    public function sendVerificationEmail(string $email, string $verificationUrl): bool;
    public function sendPasswordResetEmail(string $email, string $resetUrl): bool;
    public function sendWelcomeEmail(string $email, array $userData): bool;
}