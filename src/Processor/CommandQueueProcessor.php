<?php

namespace App\Processor;

use App\Command\CommandInterface;
use App\Command\CommandQueue;
use App\Command\FailedRetryCommand;
use App\Command\RetryableCommandInterface;
use App\Command\RetryCommand;
use App\ExceptionHandler\LoggingExceptionHandler;
use App\ExceptionHandler\RetryExceptionHandler;
use Exception;

class CommandQueueProcessor
{
    private CommandQueue $commandQueue;
    private LoggingExceptionHandler $loggingHandler;
    private RetryExceptionHandler $retryHandler;

    public function __construct(CommandQueue $commandQueue)
    {
        $this->commandQueue = $commandQueue;
        $this->loggingHandler = new LoggingExceptionHandler($this->commandQueue);
        $this->retryHandler = new RetryExceptionHandler($this->commandQueue);
    }

    public function process(): void
    {
        while ($this->commandQueue->count() > 0) {
            $command = $this->commandQueue->remove();

            try {
                if ($command instanceof CommandInterface) {
                    $command->execute();
                } else {
                    throw new Exception('Command does not implement CommandInterface.');
                }
            } catch (Exception $e) {
                $originCommand = $command instanceof RetryCommand ? $command->getOriginCommand() : $command;

                if ($originCommand instanceof RetryableCommandInterface) {
                    $this->handleExceptionForRetryableCommand($e, $originCommand);

                    continue;
                }
                $this->loggingHandler->handle($e);
            }
        }
    }

    /**
     * При первом и втором выбросе повторяем команду.
     * При третьем выбросе создаем команду для записи в лог и не оставляем оригинальную команду в очереди.
     */
    private function handleExceptionForRetryableCommand(Exception $e, RetryableCommandInterface $command): void
    {
        $command->increaseRetryCount();

        if ($command->getRetryCount() <= 2) {
            $this->retryHandler->handle($e, $command);

            return;
        }

        $this->commandQueue->add(
            new FailedRetryCommand($this->loggingHandler, $e)
        );
    }
}