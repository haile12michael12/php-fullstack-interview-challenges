<?php

namespace App\Command;

use App\Repository\SqliteEventRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Database migration command
 * 
 * Initializes the database schema for event storage
 */
class MigrateCommand extends Command
{
    protected static $defaultName = 'app:migrate';
    protected static $defaultDescription = 'Initialize the database schema';

    private SqliteEventRepository $repository;

    /**
     * Constructor
     *
     * @param SqliteEventRepository $repository The event repository
     */
    public function __construct(SqliteEventRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription(self::$defaultDescription);
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input The input interface
     * @param OutputInterface $output The output interface
     * @return int The exit code
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        try {
            // The repository automatically initializes the database
            $io->success('Database schema initialized successfully!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Failed to initialize database: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}