<?php

namespace App\Async\State;

use App\Async\Processor\AsyncCommandQueueProcessor;

class HardStopState extends CommandState
{
    public function handle(AsyncCommandQueueProcessor $processor): ?CommandState
    {
        return null; // Завершение работы потока
    }
}
