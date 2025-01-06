<?php

namespace App\Tests\Command;

use App\Command\CheckFuelCommand;
use App\Command\FuelTankInterface;
use App\Exception\CommandException;
use PHPUnit\Framework\TestCase;

class CheckFuelCommandTest extends TestCase
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

        $checkFuelCommand = new CheckFuelCommand($fuelTankMock);

        self::expectException(CommandException::class);
        self::expectExceptionMessage('Not enough fuel to execute the command.');

        $checkFuelCommand->execute();
    }

    /**
     * Не ожидается исключение CommandException.
     *
     * @throws CommandException
     */
    public function testExecuteDoesNotThrowExceptionWhenEnoughFuel(): void
    {
        $fuelTankMock = self::createMock(FuelTankInterface::class);
        $fuelTankMock->expects(self::once())
            ->method('hasEnoughFuel')
            ->willReturn(true);

        $checkFuelCommand = new CheckFuelCommand($fuelTankMock);
        $checkFuelCommand->execute();

        self::assertTrue(true);
    }
}