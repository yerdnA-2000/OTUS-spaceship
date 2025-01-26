<?php

namespace App\Async\State;

use App\Async\Processor\AsyncCommandQueueProcessor;

abstract class CommandState
{
    abstract public function handle(AsyncCommandQueueProcessor $processor): ?CommandState;
}