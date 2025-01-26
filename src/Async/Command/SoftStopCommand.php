<?php

namespace App\Async\Command;

use App\Async\Processor\AsyncCommandQueueProcessor;
use App\Async\State\SoftStopState;
use App\Command\CommandInterface;

class SoftStopCommand implements CommandInterface
{
    public function __construct(private readonly AsyncCommandQueueProcessor $processor)
    {
    }

    public function execute(): void
    {
        $this->processor->setState(new SoftStopState());
    }
}
