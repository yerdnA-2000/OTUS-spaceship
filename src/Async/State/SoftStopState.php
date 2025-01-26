<?php

namespace App\Async\State;

use App\Async\Processor\AsyncCommandQueueProcessor;

class SoftStopState extends CommandState
{
    public function handle(AsyncCommandQueueProcessor $processor): ?CommandState
    {
        if ($processor->getQueue()->isEmpty()) {
            return null; // Если очередь пуста, то завершаем работу потока
        }

        return $this;
    }
}
