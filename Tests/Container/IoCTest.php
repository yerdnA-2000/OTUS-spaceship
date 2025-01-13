<?php

namespace App\Tests\Container;

use App\Container\IoC;
use App\Container\DependencyBag;
use App\Exception\IoCException;
use PHPUnit\Framework\TestCase;
use stdClass;

class IoCTest extends TestCase
{
    /**
     * @throws IoCException
     */
    public function testResolve(): void
    {
        $bindings = new DependencyBag([
            'testService' => function () {
                return new stdClass();
            },
        ]);
        IoC::setInstance(new IoC($bindings));

        $service = IoC::resolve('testService');

        self::assertInstanceOf(stdClass::class, $service);
    }

    public function testResolveThrowsExceptionForUnknownKey(): void
    {
        IoC::setInstance(new IoC(new DependencyBag()));

        self::expectException(IoCException::class);
        self::expectExceptionMessage('No dependency found for key: unknownService');

        IoC::resolve('unknownService');
    }
}