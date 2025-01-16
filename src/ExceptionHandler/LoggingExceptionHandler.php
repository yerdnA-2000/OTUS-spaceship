<?php

namespace App\ExceptionHandler;

use App\Command\LogExceptionCommand;
use App\Command\Queue\CommandQueueInterface;
use Exception;

class LoggingExceptionHandler
{
    private CommandQueueInterface $commandQueue;

    public function __construct(CommandQueueInterface $commandQueue)
    {
        $this->commandQueue = $commandQueue;
    }

    public function handle(Exception $e): void
    {
        $logCommand = new LogExceptionCommand($e->getMessage());

        $this->commandQueue->add($logCommand);
    }
}