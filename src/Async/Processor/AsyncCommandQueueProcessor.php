<?php

namespace App\Async\Processor;

use App\Async\AsyncInvokableInterface;
use App\Async\StopState;
use App\Command\Queue\CommandSplQueue;

class AsyncCommandQueueProcessor implements AsyncInvokableInterface
{
    public function __construct(
        protected CommandSplQueue $queue,
        protected StopState $stopState,
    ) {
    }

    public function __invoke(): void
    {
        $this->process();
    }

    public function process(): void
    {
        while (!$this->stopState->isHardStopped()) {
            if ($this->queue->isEmpty()) {
                if ($this->stopState->isSoftStopped()) {
                    break;
                }
                usleep(100000); // Задержка, чтобы избежать активного ожидания новой команды

                continue;
            }
            $this->processCommand();
        }
    }

    private function processCommand(): void
    {
        try {
            $cmd = $this->queue->dequeue();
            $cmd->execute();
        } catch (\Exception $e) {
            // Ловим исключение и продолжаем выполнение
        }
    }
}
