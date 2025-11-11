<?php

namespace App\Services\Implementations;

use App\Services\Interfaces\MailerInterface;

class SmtpMailer implements MailerInterface
{
    private string $host;
    private int $port;
    private string $username;
    private string $password;
    private string $from = '';
    private string $replyTo = '';

    public function __construct(string $host, int $port, string $username, string $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    public function send(string $to, string $subject, string $body): bool
    {
        // In a real implementation, this would connect to an SMTP server
        // For this example, we'll just log the email
        $headers = [];
        if ($this->from) {
            $headers[] = "From: {$this->from}";
        }
        if ($this->replyTo) {
            $headers[] = "Reply-To: {$this->replyTo}";
        }
        
        $headerString = implode("\r\n", $headers);
        
        // Log the email instead of actually sending it
        error_log("Email to: {$to}\nSubject: {$subject}\nBody: {$body}\nHeaders: {$headerString}");
        
        return true;
    }

    public function setFrom(string $from): void
    {
        $this->from = $from;
    }

    public function setReplyTo(string $replyTo): void
    {
        $this->replyTo = $replyTo;
    }

    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}