<?php

namespace App\Tests\Command\IoC;

use App\Command\IoC\UnregisterDependencyCommand;
use App\Container\IoC;
use App\Container\DependencyBag;
use App\Exception\IoCException;
use PHPUnit\Framework\TestCase;
use stdClass;

class UnregisterDependencyCommandTest extends TestCase
{
    /**
     * @throws IoCException
     */
    public function testExecuteUsingIoC(): void
    {
        $depBag = new DependencyBag([
            'ioc.unregister' => function (string $key) use (&$depBag) {
                return new UnregisterDependencyCommand($depBag, $key);
            },
            'testService' => function () {
                return new stdClass();
            }
        ]);
        IoC::setInstance(new IoC($depBag));

        $service = IoC::resolve('testService');

        self::assertInstanceOf(stdClass::class, $service);

        IoC::resolve('ioc.unregister', 'testService')->execute();

        self::expectException(IoCException::class);
        self::expectExceptionMessage('No dependency found for key: testService');

        $service = IoC::resolve('testService');
    }
}