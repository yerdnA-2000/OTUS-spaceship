<?php

namespace App\ExceptionHandler;

use App\Command\CommandQueue;
use App\Command\LogExceptionCommand;
use Exception;

class LoggingExceptionHandler
{
    private CommandQueue $commandQueue;

    public function __construct(CommandQueue $commandQueue)
    {
        $this->commandQueue = $commandQueue;
    }

    public function handle(Exception $e): void
    {
        $logCommand = new LogExceptionCommand($e->getMessage());

        $this->commandQueue->add($logCommand);
    }
}