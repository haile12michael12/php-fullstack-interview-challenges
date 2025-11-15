<?php

namespace App\Boot;

use App\Command\MigrateCommand;
use App\Messenger\Handler\PublishNewsHandler;
use App\Observer\EmailNotifier;
use App\Observer\NewsChannel;
use App\Observer\SmsNotifier;
use App\Repository\EventRepositoryInterface;
use App\Repository\SqliteEventRepository;
use App\Service\EventService;
use App\Subject\NewsAgency;
use App\Subject\UserEventManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Container configuration
 * 
 * Configures dependency injection for the application
 */
class Container
{
    /**
     * Build the service container
     *
     * @return ContainerInterface The service container
     */
    public static function build(): ContainerInterface
    {
        $containerBuilder = new ContainerBuilder();

        // Repository
        $containerBuilder->register(SqliteEventRepository::class)
            ->addArgument($_ENV['DATABASE_PATH'] ?? ':memory:');

        $containerBuilder->setAlias(EventRepositoryInterface::class, SqliteEventRepository::class);

        // Subjects
        $containerBuilder->register(NewsAgency::class)
            ->addArgument('Global News Agency');

        $containerBuilder->register(UserEventManager::class);

        // Service
        $containerBuilder->register(EventService::class)
            ->addArgument(new Reference(NewsAgency::class))
            ->addArgument(new Reference(UserEventManager::class));

        // Observers
        $containerBuilder->register(NewsChannel::class . '.bbc')
            ->setClass(NewsChannel::class)
            ->addArgument('BBC');

        $containerBuilder->register(NewsChannel::class . '.cnn')
            ->setClass(NewsChannel::class)
            ->addArgument('CNN');

        $containerBuilder->register(EmailNotifier::class)
            ->addArgument('Email Notifier')
            ->addArgument('admin@example.com');

        $containerBuilder->register(SmsNotifier::class)
            ->addArgument('SMS Notifier')
            ->addArgument('+1234567890');

        // Messenger Handler
        $containerBuilder->register(PublishNewsHandler::class)
            ->addArgument(new Reference(EventService::class));

        // Command
        $containerBuilder->register(MigrateCommand::class)
            ->addArgument(new Reference(SqliteEventRepository::class))
            ->addTag('console.command');

        $containerBuilder->compile();

        return $containerBuilder;
    }
}