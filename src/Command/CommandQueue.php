<?php

namespace App\Command;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

class CommandQueue implements Countable, IteratorAggregate, ArrayAccess
{
    /**
     * @var CommandInterface[]
     */
    private array $commands = [];

    /**
     * @param CommandInterface[] $commands
     */
    public function __construct(array $commands = [])
    {
        foreach ($commands as $command) {
            $this->add($command);
        }
    }

    public function add(CommandInterface $command): void
    {
        $this->commands[] = $command;
    }

    public function remove(): ?CommandInterface
    {
        return array_shift($this->commands);
    }

    public function count(): int
    {
        return count($this->commands);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->commands);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->commands[$offset]);
    }

    public function offsetGet($offset): CommandInterface
    {
        return $this->commands[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->commands[] = $value;
        } else {
            $this->commands[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->commands[$offset]);
    }
}