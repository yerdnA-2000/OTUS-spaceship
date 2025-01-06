<?php

namespace App\Command;

class RetryCommand implements CommandInterface
{
    private RetryableCommandInterface $command;

    public function __construct(RetryableCommandInterface $command)
    {
        $this->command = $command;
    }

    public function execute(): void
    {
        $this->command->execute();
    }

    public function getOriginCommand(): RetryableCommandInterface
    {
        return $this->command;
    }
}