<?php

namespace App\Async\State;

use App\Async\Processor\AsyncCommandQueueProcessor;
use App\Command\Queue\CommandSplQueueInterface;

class MoveToState extends CommandState
{
    public function __construct(
        private readonly CommandSplQueueInterface $toQueue,
        private bool $autoStop = false,
    ) {
    }

    public function handle(AsyncCommandQueueProcessor $processor): ?CommandState
    {
        $fromQueue = $processor->getQueue();

        if ($fromQueue === $this->toQueue) {
            throw new \LogicException('Вы пытаетесь переместить команды в ту же самую очередь.');
        }

        while (!$fromQueue->isEmpty()) {
            $this->toQueue->enqueue($fromQueue->dequeue());
        }
        if ($this->autoStop) {
            return (new HardStopState())->handle($processor);
        }

        return new NormalState();
    }
}
