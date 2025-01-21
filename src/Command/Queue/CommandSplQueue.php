<?php

namespace App\Command\Queue;

use App\Command\CommandInterface;
use SplQueue;

/**
 * Потокобезопасная очередь
 */
class CommandSplQueue implements CommandSplQueueInterface
{
    private SplQueue $queue;

    /**
     * @param CommandInterface[] $commands
     */
    public function __construct(iterable $commands = [])
    {
        $this->queue = new SplQueue();

        foreach ($commands as $command) {
            $this->enqueue($command);
        }
    }

    public function enqueue(CommandInterface $command): void
    {
        $this->queue->enqueue($command);
    }

    public function dequeue(): ?CommandInterface
    {
        return $this->queue->isEmpty() ? null : $this->queue->dequeue();
    }

    public function isEmpty(): bool
    {
        return $this->queue->isEmpty();
    }
}