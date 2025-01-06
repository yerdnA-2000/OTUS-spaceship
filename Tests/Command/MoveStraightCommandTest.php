<?php

namespace App\Tests\Command;

use App\Command\FuelTankInterface;
use App\Command\MoveStraightCommand;
use App\Exception\CommandException;
use App\Move\MoveCommand;
use PHPUnit\Framework\TestCase;

class MoveStraightCommandTest extends TestCase
{
    /**
     * Не ожидается исключение CommandException.
     *
     * @throws CommandException
     */
    public function testExecuteMovesSuccessfullyWithEnoughFuel(): void
    {
        $fuelTankMock = self::createMock(FuelTankInterface::class);
        $fuelTankMock->expects(self::exactly(2))
            ->method('hasEnoughFuel')
            ->willReturn(true);

        $moveMock = self::createMock(MoveCommand::class);
        $moveMock->expects(self::once())
            ->method('execute');

        $fuelConsumption = 10.0;

        $moveStraightCommand = new MoveStraightCommand($fuelTankMock, $moveMock, $fuelConsumption);

        $moveStraightCommand->execute();

        self::assertTrue(true);
    }

    /**
     * Ожидается исключение CommandException.
     */
    public function testExecuteThrowsExceptionWhenNotEnoughFuel(): void
    {
        $fuelTankMock = self::createMock(FuelTankInterface::class);
        $fuelTankMock->expects(self::once())
            ->method('hasEnoughFuel')
            ->willReturn(false);

        $moveMock = self::createMock(MoveCommand::class);

        $fuelConsumption = 10.0;

        $moveStraightCommand = new MoveStraightCommand($fuelTankMock, $moveMock, $fuelConsumption);

        self::expectException(CommandException::class);
        self::expectExceptionMessage('Not enough fuel to execute the command.');

        $moveStraightCommand->execute();
    }
}