<?php

namespace App\Command;

use App\Config\Config;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class LogExceptionCommand implements CommandInterface
{
    private Logger $logger;
    private string $message;

    public function __construct(string $message)
    {
        $this->logger = new Logger('exception_logger');

        $this->logger->pushHandler(new StreamHandler(Config::EXCEPTION_LOGS_PATH, Level::Error->value));

        $this->message = $message;
    }

    public function execute(): void
    {
        $this->logger->error($this->message);
    }
}