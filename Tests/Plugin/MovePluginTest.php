<?php

namespace App\Tests\Plugin;

use App\Command\FuelTankInterface;
use App\Command\MoveStraightCommand;
use App\Container\IoC;
use App\Exception\IoCException;
use App\Move\MoveCommand;
use App\Plugin\MovePlugin;
use App\Tests\TestCase\BaseIoCUsingTestTrait;
use PHPUnit\Framework\TestCase;

class MovePluginTest extends TestCase
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
        (new MovePlugin())->load();

        $fuelTank = self::createMock(FuelTankInterface::class);
        $moveCommand = self::createMock(MoveCommand::class);

        $moveCommand = IoC::resolve('moveStraight', $fuelTank, $moveCommand, 15.3);
        self::assertInstanceOf(MoveStraightCommand::class, $moveCommand);
    }
}