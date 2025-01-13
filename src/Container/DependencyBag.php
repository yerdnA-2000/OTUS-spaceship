<?php

namespace App\Container;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * Хранит ассоциации между ключами и замыканиями для разрешения зависимостей.
 */
class DependencyBag implements Countable, IteratorAggregate, ArrayAccess
{
    private array $dependencies;

    public function __construct(array $dependencies = [])
    {
        foreach ($dependencies as $key => $resolver) {
            $this->add($key, $resolver);
        }
    }

    public function add(string $key, \Closure $resolver): void
    {
        $this->dependencies[$key] = $resolver;
    }

    public function remove(string $key): void
    {
        $this->offsetUnset($key);
    }

    public function count(): int
    {
        return count($this->dependencies);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->dependencies);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->dependencies[$offset]);
    }

    public function offsetGet($offset): \Closure
    {
        return $this->dependencies[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->dependencies[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->dependencies[$offset]);
    }
}