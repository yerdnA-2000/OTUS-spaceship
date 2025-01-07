<?php

namespace App\Tests\Plugin\IoC;

use App\Command\FuelTankInterface;
use App\Command\MoveStraightCommand;
use App\Container\IoC;
use App\Exception\IoCException;
use App\Move\MoveCommand;
use App\Plugin\IoC\MovePlugin;
use PHPUnit\Framework\TestCase;

class MovePluginTest extends TestCase
{
    private IoC $ioc;

    protected function setUp(): void
    {
        $this->ioc = new IoC();

        $this->ioc->loadPlugins([new MovePlugin()]);
    }

    /**
     * @throws IoCException
     */
    public function testLoadPluginsAndResolve(): void
    {
        $fuelTank = self::createMock(FuelTankInterface::class);
        $moveCommand = self::createMock(MoveCommand::class);

        $moveCommand = $this->ioc->resolve('moveStraight', $fuelTank, $moveCommand, 15.3);
        self::assertInstanceOf(MoveStraightCommand::class, $moveCommand);
    }
}