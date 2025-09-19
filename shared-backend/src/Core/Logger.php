<?php

declare(strict_types=1);

namespace SharedBackend\Core;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Handler\RedisHandler;
use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Advanced logging system with multiple handlers and processors
 */
class Logger implements LoggerInterface
{
    private MonologLogger $logger;
    private array $context = [];

    public function __construct(Config $config)
    {
        $this->logger = new MonologLogger('app');
        $this->setupHandlers($config);
        $this->setupProcessors();
    }

    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->logger->emergency($message, array_merge($this->context, $context));
    }

    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->logger->alert($message, array_merge($this->context, $context));
    }

    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->logger->critical($message, array_merge($this->context, $context));
    }

    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->logger->error($message, array_merge($this->context, $context));
    }

    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->logger->warning($message, array_merge($this->context, $context));
    }

    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->logger->notice($message, array_merge($this->context, $context));
    }

    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->logger->info($message, array_merge($this->context, $context));
    }

    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->logger->debug($message, array_merge($this->context, $context));
    }

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->logger->log($level, $message, array_merge($this->context, $context));
    }

    public function addContext(array $context): void
    {
        $this->context = array_merge($this->context, $context);
    }

    public function removeContext(string $key): void
    {
        unset($this->context[$key]);
    }

    public function clearContext(): void
    {
        $this->context = [];
    }

    public function withContext(array $context): self
    {
        $newLogger = clone $this;
        $newLogger->addContext($context);
        return $newLogger;
    }

    private function setupHandlers(Config $config): void
    {
        $logLevel = $config->get('logging.channels.single.level', LogLevel::DEBUG);
        $logPath = $config->get('logging.channels.single.path', '/tmp/logs/app.log');

        // Ensure log directory exists
        $logDir = dirname($logPath);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        // File handler with rotation
        $fileHandler = new RotatingFileHandler($logPath, 30, LogLevel::DEBUG);
        $fileHandler->setFormatter(new LineFormatter(
            "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
            'Y-m-d H:i:s'
        ));
        $this->logger->pushHandler($fileHandler);

        // Console handler for development
        if ($config->isDevelopment()) {
            $consoleHandler = new StreamHandler('php://stdout', LogLevel::DEBUG);
            $consoleHandler->setFormatter(new LineFormatter(
                "<fg=%level_color%>%datetime% %level_name%</>: %message% %context%\n"
            ));
            $this->logger->pushHandler($consoleHandler);
        }

        // JSON handler for structured logging
        $jsonHandler = new StreamHandler($logPath . '.json', LogLevel::INFO);
        $jsonHandler->setFormatter(new JsonFormatter());
        $this->logger->pushHandler($jsonHandler);

        // Syslog handler for production
        if ($config->isProduction()) {
            $syslogHandler = new SyslogHandler('php-challenge', LOG_USER, LogLevel::WARNING);
            $this->logger->pushHandler($syslogHandler);
        }
    }

    private function setupProcessors(): void
    {
        $this->logger->pushProcessor(new UidProcessor());
        $this->logger->pushProcessor(new WebProcessor());
        $this->logger->pushProcessor(new MemoryUsageProcessor());
        $this->logger->pushProcessor(new PsrLogMessageProcessor());
        
        // Custom processor for request ID
        $this->logger->pushProcessor(function (array $record) {
            $record['extra']['request_id'] = $_SERVER['HTTP_X_REQUEST_ID'] ?? uniqid();
            return $record;
        });
    }
}
