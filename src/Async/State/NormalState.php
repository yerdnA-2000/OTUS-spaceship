<?php

namespace App\Async\State;

use App\Async\Processor\AsyncCommandQueueProcessor;

class NormalState extends CommandState
{
    public function handle(AsyncCommandQueueProcessor $processor): ?CommandState
    {
        return $this; // Возврат к обычному состоянию
    }
}
