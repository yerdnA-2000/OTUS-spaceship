<?php

namespace App\Tests\Container;

use App\Container\IoC;
use App\Exception\IoCException;
use PHPUnit\Framework\TestCase;
use stdClass;

class IoCTest extends TestCase
{
    private IoC $ioc;

    protected function setUp(): void
    {
        $this->ioc = new IoC();
    }

    /**
     * @throws IoCException
     */
    public function testRegisterAndResolve(): void
    {
        $this->ioc->register('testService', function() {
            return new stdClass();
        });

        $service = $this->ioc->resolve('testService');

        self::assertInstanceOf(stdClass::class, $service);
    }

    public function testUnregister(): void
    {
        $this->ioc->register('testService', function() {
            return new stdClass();
        });

        $this->ioc->unregister('testService');

        self::expectException(IoCException::class);
        self::expectExceptionMessage('No binding found for key: testService');

        $this->ioc->resolve('testService');
    }

    public function testResolveThrowsExceptionForUnknownKey(): void
    {
        self::expectException(IoCException::class);
        self::expectExceptionMessage('No binding found for key: unknownService');

        $this->ioc->resolve('unknownService');
    }
}