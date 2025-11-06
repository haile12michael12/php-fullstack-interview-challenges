<?php

namespace Challenge02\Infrastructure\Mail;

use Challenge02\Contracts\Services\MailServiceInterface;

class LegacyMailService implements MailServiceInterface
{
    public function sendVerificationEmail(string $email, string $verificationUrl): bool
    {
        // In a real implementation, you would use PHPMailer or another mail library
        // For now, we'll just log the email
        error_log("Sending verification email to {$email} with URL: {$verificationUrl}");
        return true;
    }

    public function sendPasswordResetEmail(string $email, string $resetUrl): bool
    {
        // In a real implementation, you would use PHPMailer or another mail library
        // For now, we'll just log the email
        error_log("Sending password reset email to {$email} with URL: {$resetUrl}");
        return true;
    }

    public function sendWelcomeEmail(string $email, array $userData): bool
    {
        // In a real implementation, you would use PHPMailer or another mail library
        // For now, we'll just log the email
        error_log("Sending welcome email to {$email}");
        return true;
    }
}