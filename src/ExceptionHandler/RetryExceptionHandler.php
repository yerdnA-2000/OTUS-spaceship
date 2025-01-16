<?php

namespace App\ExceptionHandler;

use App\Command\Queue\CommandQueueInterface;
use App\Command\RetryableCommandInterface;
use App\Command\RetryCommand;
use Exception;

class RetryExceptionHandler
{
    private CommandQueueInterface $commandQueue;

    public function __construct(CommandQueueInterface $commandQueue)
    {
        $this->commandQueue = $commandQueue;
    }

    public function handle(Exception $e, RetryableCommandInterface $failedCommand): void
    {
        $retryCommand = new RetryCommand($failedCommand);

        $this->commandQueue->add($retryCommand);
    }
}