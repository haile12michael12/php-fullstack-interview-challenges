<?php

namespace App\Receiver;

class EmailService
{
    private string $logPath;

    public function __construct(string $logPath = './storage/emails.log')
    {
        $this->logPath = $logPath;
        $dir = dirname($this->logPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    /**
     * Send an email
     *
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param array $attachments
     * @return bool
     */
    public function send(string $to, string $subject, string $body, array $attachments = []): bool
    {
        // In a real implementation, this would send an actual email
        // For this example, we'll just log the email
        
        $emailData = [
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
            'attachments' => $attachments,
            'sent_at' => date('c')
        ];
        
        $logEntry = "[" . date('c') . "] EMAIL SENT: " . json_encode($emailData) . "\n";
        
        return file_put_contents($this->logPath, $logEntry, FILE_APPEND | LOCK_EX) !== false;
    }

    /**
     * Send a templated email
     *
     * @param string $to
     * @param string $template
     * @param array $variables
     * @return bool
     */
    public function sendTemplate(string $to, string $template, array $variables = []): bool
    {
        $subject = $this->renderTemplate($template . '_subject', $variables);
        $body = $this->renderTemplate($template . '_body', $variables);
        
        return $this->send($to, $subject, $body);
    }

    /**
     * Get email log
     *
     * @param int $limit
     * @return array
     */
    public function getEmailLog(int $limit = 10): array
    {
        if (!file_exists($this->logPath)) {
            return [];
        }
        
        $lines = file($this->logPath, FILE_IGNORE_NEW_LINES);
        $emails = [];
        
        // Get the last $limit lines
        $lines = array_slice($lines, -$limit);
        
        foreach ($lines as $line) {
            if (preg_match('/\[([^\]]+)\] EMAIL SENT: (.+)/', $line, $matches)) {
                $emails[] = [
                    'timestamp' => $matches[1],
                    'data' => json_decode($matches[2], true)
                ];
            }
        }
        
        return array_reverse($emails);
    }

    /**
     * Clear email log
     *
     * @return bool
     */
    public function clearLog(): bool
    {
        if (!file_exists($this->logPath)) {
            return true;
        }
        
        return unlink($this->logPath);
    }

    /**
     * Render a template with variables
     *
     * @param string $template
     * @param array $variables
     * @return string
     */
    private function renderTemplate(string $template, array $variables): string
    {
        $content = $template;
        
        foreach ($variables as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        
        return $content;
    }

    /**
     * Validate email address
     *
     * @param string $email
     * @return bool
     */
    public function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}