<?php

namespace App\Async\Command;

use App\Async\Processor\AsyncCommandQueueProcessor;
use App\Async\State\MoveToState;
use App\Command\CommandInterface;
use App\Command\Queue\CommandSplQueueInterface;

class MoveToCommand implements CommandInterface
{
    public function __construct(
        private AsyncCommandQueueProcessor $processor,
        private CommandSplQueueInterface $toQueue,
        private bool $stopAfter = false,
    ) {
    }

    public function execute(): void
    {
        $this->processor->setState(new MoveToState($this->toQueue, $this->stopAfter));
    }
}