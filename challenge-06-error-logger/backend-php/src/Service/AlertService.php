<?php

namespace App\Service;

class AlertService
{
    private string $alertEmail;
    private string $alertWebhook;

    public function __construct(string $alertEmail = '', string $alertWebhook = '')
    {
        $this->alertEmail = $alertEmail;
        $this->alertWebhook = $alertWebhook;
    }

    public function sendAlert(string $message, string $level = 'error'): bool
    {
        $alertMessage = sprintf("[%s] %s: %s", date('Y-m-d H:i:s'), strtoupper($level), $message);
        
        $success = true;
        
        if (!empty($this->alertEmail)) {
            $success = $success && $this->sendEmailAlert($alertMessage);
        }
        
        if (!empty($this->alertWebhook)) {
            $success = $success && $this->sendWebhookAlert($alertMessage);
        }
        
        return $success;
    }

    private function sendEmailAlert(string $message): bool
    {
        // Implementation for sending email alert
        return mail($this->alertEmail, 'Application Alert', $message);
    }

    private function sendWebhookAlert(string $message): bool
    {
        // Implementation for sending webhook alert
        if (empty($this->alertWebhook)) {
            return false;
        }
        
        $data = json_encode(['message' => $message, 'timestamp' => date('Y-m-d H:i:s')]);
        
        $options = [
            'http' => [
                'header' => "Content-Type: application/json\r\n",
                'method' => 'POST',
                'content' => $data,
            ],
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($this->alertWebhook, false, $context);
        
        return $result !== false;
    }
}