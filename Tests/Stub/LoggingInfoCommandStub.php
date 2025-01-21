<?php

namespace App\Tests\Stub;

use App\Command\CommandInterface;
use Monolog\Logger;

class LoggingInfoCommandStub implements CommandInterface
{
    public function __construct(private string $name, private Logger $logger)
    {
    }

    public function execute(): void
    {
        $this->logger->info("Command {$this->name} executed");
    }

    public function getName(): string
    {
        return $this->name;
    }
}