<?php

namespace App\Command\Queue;

use App\Command\CommandInterface;

interface CommandSplQueueInterface
{
    public function enqueue(CommandInterface $command): void;

    public function dequeue(): ?CommandInterface;

    public function isEmpty(): bool;
}