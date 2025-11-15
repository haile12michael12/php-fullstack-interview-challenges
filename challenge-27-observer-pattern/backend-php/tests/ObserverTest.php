<?php

namespace Tests;

use App\Observer\EmailNotifier;
use App\Observer\NewsChannel;
use App\Subject\NewsAgency;
use App\Subject\UserEventManager;
use PHPUnit\Framework\TestCase;

class ObserverTest extends TestCase
{
    public function testObserverPatternWithNewsAgency(): void
    {
        // Create subjects
        $newsAgency = new NewsAgency('Test News Agency');
        $userEventManager = new UserEventManager();

        // Create observers
        $bbc = new NewsChannel('BBC');
        $emailNotifier = new EmailNotifier('Email Notifier', 'test@example.com');

        // Attach observers to subjects
        $newsAgency->attach($bbc);
        $newsAgency->attach($emailNotifier);
        $userEventManager->attach($emailNotifier);

        // Publish news
        $newsAgency->publishNews('Test News', 'This is a test news article.');

        // Record user events
        $userEventManager->recordUserRegistration('user123', 'user@example.com');

        // Assertions
        $this->assertCount(1, $bbc->getReceivedNews());
        $this->assertCount(2, $emailNotifier->getSentEmails());
    }

    public function testObserverDetachment(): void
    {
        // Create subject and observer
        $newsAgency = new NewsAgency('Test News Agency');
        $bbc = new NewsChannel('BBC');

        // Attach and then detach observer
        $newsAgency->attach($bbc);
        $newsAgency->detach($bbc);

        // Publish news
        $newsAgency->publishNews('Test News', 'This is a test news article.');

        // BBC should not have received any news since it was detached
        $this->assertCount(0, $bbc->getReceivedNews());
    }

    public function testMultipleObservers(): void
    {
        // Create subject
        $newsAgency = new NewsAgency('Test News Agency');

        // Create multiple observers
        $bbc = new NewsChannel('BBC');
        $cnn = new NewsChannel('CNN');
        $emailNotifier = new EmailNotifier('Email Notifier', 'test@example.com');

        // Attach all observers
        $newsAgency->attach($bbc);
        $newsAgency->attach($cnn);
        $newsAgency->attach($emailNotifier);

        // Publish news
        $newsAgency->publishNews('Test News', 'This is a test news article.');

        // All observers should have been notified
        $this->assertCount(1, $bbc->getReceivedNews());
        $this->assertCount(1, $cnn->getReceivedNews());
        $this->assertCount(1, $emailNotifier->getSentEmails());
    }
}