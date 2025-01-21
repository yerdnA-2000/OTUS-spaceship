<?php

namespace App\Async\Command;

use App\Async\StopState;
use App\Command\CommandInterface;

class SoftStopCommand implements CommandInterface
{
    public function __construct(private readonly StopState $stopState)
    {
    }

    public function execute(): void
    {
        $this->stopState->softStop();
    }
}
