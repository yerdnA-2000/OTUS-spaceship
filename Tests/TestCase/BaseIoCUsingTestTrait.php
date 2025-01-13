<?php

namespace App\Tests\TestCase;

use App\Command\IoC\RegisterDependencyCommand;
use App\Command\IoC\UnregisterDependencyCommand;
use App\Container\IoC;
use App\Container\DependencyBag;

trait BaseIoCUsingTestTrait
{
    private function initIoC(): void
    {
        $bindings = new DependencyBag([
            'ioc.register' => function (string $key, \Closure $resolver) use (&$bindings) {
                return new RegisterDependencyCommand($bindings, $key, $resolver);
            },
            'ioc.unregister' => function (string $key) use (&$bindings) {
                return new UnregisterDependencyCommand($bindings, $key);
            },
        ]);
        IoC::setInstance(new IoC($bindings));
    }
}