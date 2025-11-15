<?php

namespace App\Observer;

/**
 * Email notifier observer implementation
 * 
 * Sends email notifications for various events
 */
class EmailNotifier extends AbstractObserver
{
    private string $emailAddress;
    private array $sentEmails = [];

    /**
     * Constructor
     *
     * @param string $name The name of the notifier
     * @param string $emailAddress The email address to send notifications to
     */
    public function __construct(string $name, string $emailAddress)
    {
        parent::__construct($name);
        $this->emailAddress = $emailAddress;
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
        $subjectName = is_object($subject) ? get_class($subject) : 'Unknown';

        switch ($event) {
            case 'user.login':
                $message = "User {$data['user_id']} logged in from IP {$data['ip_address']}";
                $this->sendEmail("Login Alert", $message);
                break;
                
            case 'user.registration':
                $message = "New user registered: {$data['user_id']} ({$data['email']})";
                $this->sendEmail("Registration Alert", $message);
                break;
                
            case 'user.action':
                $message = "User {$data['user_id']} performed action: {$data['action']}";
                $this->sendEmail("User Action Alert", $message);
                break;
                
            case 'news.published':
                $message = "New news published: {$data['title']}";
                $this->sendEmail("News Alert", $message);
                break;
        }
    }

    /**
     * Send an email notification
     *
     * @param string $subject The email subject
     * @param string $message The email message
     * @return void
     */
    private function sendEmail(string $subject, string $message): void
    {
        // In a real implementation, this would send an actual email
        // For this example, we'll just log it
        $email = [
            'to' => $this->emailAddress,
            'subject' => $subject,
            'message' => $message,
            'sent_at' => new \DateTimeImmutable()
        ];

        $this->sentEmails[] = $email;
        
        echo "[EmailNotifier: {$this->getName()}] Email sent to {$this->emailAddress}: {$subject}\n";
    }

    /**
     * Get all sent emails
     *
     * @return array The list of sent emails
     */
    public function getSentEmails(): array
    {
        return $this->sentEmails;
    }
}