<?php

namespace App\Command\Queue;

use App\Command\CommandInterface;
use ArrayAccess;
use Countable;
use IteratorAggregate;

interface CommandQueueInterface extends Countable, IteratorAggregate, ArrayAccess
{
    public function add(CommandInterface $command): void;

    public function remove(): ?CommandInterface;
}