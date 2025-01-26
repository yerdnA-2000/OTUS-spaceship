<?php

namespace App\Async\Command;

use App\Async\Processor\AsyncCommandQueueProcessor;
use App\Async\State\NormalState;
use App\Command\CommandInterface;

class RunCommand implements CommandInterface
{
    public function __construct(private readonly AsyncCommandQueueProcessor $processor)
    {
    }

    public function execute(): void
    {
        $this->processor->setState(new NormalState());
    }
}