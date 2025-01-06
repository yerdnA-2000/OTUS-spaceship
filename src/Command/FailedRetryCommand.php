<?php

namespace App\Command;

use App\ExceptionHandler\LoggingExceptionHandler;
use Exception;

class FailedRetryCommand implements CommandInterface
{
    private Exception $e;
    private LoggingExceptionHandler $loggingHandler;

    public function __construct(LoggingExceptionHandler $loggingHandler, Exception $e)
    {
        $this->loggingHandler = $loggingHandler;
        $this->e = $e;
    }

    public function execute(): void
    {
        $this->loggingHandler->handle($this->e);
    }
}