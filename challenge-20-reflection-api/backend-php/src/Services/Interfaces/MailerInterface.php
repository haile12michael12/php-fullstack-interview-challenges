<?php

namespace App\Services\Interfaces;

interface MailerInterface
{
    public function send(string $to, string $subject, string $body): bool;
    public function setFrom(string $from): void;
    public function setReplyTo(string $replyTo): void;
}