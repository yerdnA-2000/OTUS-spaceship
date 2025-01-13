<?php

namespace App\Command\IoC;

use App\Command\CommandInterface;
use App\Container\DependencyBag;

class UnregisterDependencyCommand implements CommandInterface
{
    public function __construct(
        private readonly DependencyBag $bindings,
        private readonly string $key,
    ) {
    }

    public function execute(): void
    {
        $this->bindings->remove($this->key);
    }
}