<?php

namespace App\Observer;

/**
 * SMS notifier observer implementation
 * 
 * Sends SMS notifications for critical events
 */
class SmsNotifier extends AbstractObserver
{
    private string $phoneNumber;
    private array $sentSms = [];

    /**
     * Constructor
     *
     * @param string $name The name of the notifier
     * @param string $phoneNumber The phone number to send SMS to
     */
    public function __construct(string $name, string $phoneNumber)
    {
        parent::__construct($name);
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Handle the update from the subject
     *
     * @param mixed $subject The subject that is being observed
     * @param string $event The event that occurred
     * @param mixed $data Additional data related to the event
     * @return void
     */
    protected function handleUpdate($subject, string $event, $data = null): void
    {
        // Only send SMS for critical events
        switch ($event) {
            case 'user.login':
                // Only send SMS for suspicious logins (in a real implementation)
                if ($this->isSuspiciousLogin($data)) {
                    $message = "Security alert: User {$data['user_id']} logged in from {$data['ip_address']}";
                    $this->sendSms($message);
                }
                break;
                
            case 'user.registration':
                $message = "New user registered: {$data['user_id']}";
                $this->sendSms($message);
                break;
        }
    }

    /**
     * Check if a login is suspicious
     *
     * @param array $loginData The login data
     * @return bool True if the login is suspicious, false otherwise
     */
    private function isSuspiciousLogin(array $loginData): bool
    {
        // In a real implementation, this would check against known patterns
        // For this example, we'll just return false to avoid spam
        return false;
    }

    /**
     * Send an SMS notification
     *
     * @param string $message The SMS message
     * @return void
     */
    private function sendSms(string $message): void
    {
        // In a real implementation, this would send an actual SMS
        // For this example, we'll just log it
        $sms = [
            'to' => $this->phoneNumber,
            'message' => $message,
            'sent_at' => new \DateTimeImmutable()
        ];

        $this->sentSms[] = $sms;
        
        echo "[SmsNotifier: {$this->getName()}] SMS sent to {$this->phoneNumber}: {$message}\n";
    }

    /**
     * Get all sent SMS messages
     *
     * @return array The list of sent SMS messages
     */
    public function getSentSms(): array
    {
        return $this->sentSms;
    }
}