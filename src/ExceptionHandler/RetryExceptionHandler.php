<?php

namespace App\ExceptionHandler;

use App\Command\CommandQueue;
use App\Command\RetryableCommandInterface;
use App\Command\RetryCommand;
use Exception;

class RetryExceptionHandler
{
    private CommandQueue $commandQueue;

    public function __construct(CommandQueue $commandQueue)
    {
        $this->commandQueue = $commandQueue;
    }

    public function handle(Exception $e, RetryableCommandInterface $failedCommand): void
    {
        $retryCommand = new RetryCommand($failedCommand);

        $this->commandQueue->add($retryCommand);
    }
}