<?php

namespace App\Tests\Plugin;

use App\Command\FuelTankInterface;
use App\Command\MoveStraightCommand;
use App\Container\IoC;
use App\Exception\IoCException;
use App\Move\MoveCommand;
use App\Move\MovingObject;
use App\Plugin\AdapterPlugin;
use App\Plugin\MovePlugin;
use App\Plugin\PluginInterface;
use App\Rotate\RotatableInterface;
use App\Tests\TestCase\BaseIoCUsingTestTrait;
use PHPUnit\Framework\TestCase;
use stdClass;

class AdapterPluginTest extends TestCase
{
    use BaseIoCUsingTestTrait;

    protected function setUp(): void
    {
        $this->initIoC();
    }

    /**
     * @throws IoCException
     */
    public function testLoadPluginAndResolve(): void
    {
        (new AdapterPlugin())->load();

        $mockObj = self::createMock(stdClass::class);

        $adapter = IoC::resolve('Adapter', PluginInterface::class, $mockObj);

        self::assertTrue(true);
    }
}