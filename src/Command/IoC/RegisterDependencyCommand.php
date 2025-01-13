<?php

namespace App\Command\IoC;

use App\Command\CommandInterface;
use App\Container\DependencyBag;
use Closure;

class RegisterDependencyCommand implements CommandInterface
{
    public function __construct(
        private readonly DependencyBag $bindings,
        private readonly string $key,
        private readonly Closure $resolver,
    ) {
    }

    public function execute(): void
    {
        $this->bindings->add($this->key, $this->resolver);
    }
}