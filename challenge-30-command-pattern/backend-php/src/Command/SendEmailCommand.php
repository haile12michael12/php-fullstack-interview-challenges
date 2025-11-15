<?php

namespace App\Command;

use App\Receiver\EmailService;

class SendEmailCommand extends AbstractCommand
{
    private EmailService $emailService;
    private string $to;
    private string $subject;
    private string $body;
    private array $attachments;

    public function __construct(
        EmailService $emailService,
        string $to,
        string $subject,
        string $body,
        array $attachments = []
    ) {
        parent::__construct(
            'Send Email',
            "Send email to: $to",
            [
                'to' => $to,
                'subject' => $subject,
                'attachments' => $attachments
            ]
        );
        
        $this->emailService = $emailService;
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
        $this->attachments = $attachments;
    }

    /**
     * Execute the command
     *
     * @return mixed
     */
    public function execute()
    {
        $result = $this->emailService->send($this->to, $this->subject, $this->body, $this->attachments);
        $this->markAsExecuted();
        return $result;
    }

    /**
     * Undo the command
     *
     * @return mixed
     */
    public function undo()
    {
        // Email sending cannot be truly undone, but we can log the attempt
        error_log("Attempted to undo email send to {$this->to} with subject '{$this->subject}'");
        $this->markAsUndone();
        return true;
    }
}