<?php

namespace App\Tests\Stub;

use App\Command\CommandInterface;
use Monolog\Logger;

class LoggingStateCommandStub implements CommandInterface
{
    public function __construct(
        private string $stateClass,
        private Logger $logger,
        private object $object,
    ) {
    }

    public function execute(): void
    {
        $refProperty = new \ReflectionProperty(get_class($this->object), 'state');
        $state = clone $refProperty->getValue($this->object);

        if (get_class($state) === $this->stateClass) {
            $this->logger->info("State is {$this->stateClass}");
        }
    }
}