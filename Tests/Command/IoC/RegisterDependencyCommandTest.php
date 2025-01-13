<?php

namespace App\Tests\Command\IoC;

use App\Command\IoC\RegisterDependencyCommand;
use App\Container\IoC;
use App\Container\DependencyBag;
use PHPUnit\Framework\TestCase;

class RegisterDependencyCommandTest extends TestCase
{
    public function testExecuteUsingIoC(): void
    {
        $depBag = new DependencyBag([
            'ioc.register' => function (string $key, \Closure $resolver) use (&$depBag) {
                return new RegisterDependencyCommand($depBag, $key, $resolver);
            },
        ]);
        IoC::setInstance(new IoC($depBag));

        IoC::resolve('ioc.register', 'testService', function() {
            return 'registered';
        })->execute();

        $service = IoC::resolve('testService');

        self::assertEquals('registered', $service);
    }
}