<?php

namespace App\Tests\Command;

use App\Command\BurnFuelCommand;
use App\Command\FuelTankInterface;
use App\Exception\CommandException;
use PHPUnit\Framework\TestCase;

class BurnFuelCommandTest extends TestCase
{
    /**
     * Ожидается исключение CommandException.
     */
    public function testExecuteThrowsExceptionWhenNotEnoughFuel(): void
    {
        $fuelTankMock = self::createMock(FuelTankInterface::class);
        $fuelTankMock->expects(self::once())
            ->method('hasEnoughFuel')
            ->willReturn(false);

        $burnFuelCommand = new BurnFuelCommand($fuelTankMock, 15.3);

        self::expectException(CommandException::class);
        self::expectExceptionMessage('Not enough fuel to burn.');

        $burnFuelCommand->execute();
    }

    /**
     * Не ожидается исключение CommandException.
     *
     * @throws CommandException
     */
    public function testExecuteBurnsFuelSuccessfully(): void
    {
        $fuelTankMock = self::createMock(FuelTankInterface::class);
        $fuelTankMock->expects(self::once())
            ->method('hasEnoughFuel')
            ->willReturn(true);

        $fuelConsumption = 10.3;
        $fuelTankMock->expects(self::once())
            ->method('burn')
            ->with(self::equalTo($fuelConsumption));

        $burnFuelCommand = new BurnFuelCommand($fuelTankMock, $fuelConsumption);
        $burnFuelCommand->execute();

        self::assertTrue(true);
    }
}