<?php

namespace App\Async\Processor;

use App\Async\AsyncInvokableInterface;
use App\Async\State\CommandState;
use App\Async\State\NormalState;
use App\Command\Queue\CommandSplQueueInterface;

class AsyncCommandQueueProcessor implements AsyncInvokableInterface
{
    protected CommandState $state;

    public function __construct(
        protected CommandSplQueueInterface $queue,
        CommandState $initialState = null,
    ) {
        $this->state = $initialState ?? new NormalState();
    }

    public function __invoke(): void
    {
        $this->process();
    }

    public function process(): void
    {
        while (null !== $this->state->handle($this)) {
            if ($this->queue->isEmpty()) {
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

    public function setState(CommandState $state): void
    {
        $this->state = $state;
    }

    public function getQueue(): CommandSplQueueInterface
    {
        return $this->queue;
    }
}
