<?php

namespace App\Tests\Command;

use App\Command\ChangeVelocityCommand;
use App\Exception\CommandException;
use App\Move\MovableInterface;
use App\Rotate\RotatableInterface;
use App\Vector\Vector;
use PHPUnit\Framework\TestCase;

class ChangeVelocityCommandTest extends TestCase
{
    /**
     * Ожидается исключение CommandException.
     */
    public function testExecuteThrowsExceptionWhenStationary(): void
    {
        $movableMock = self::createMock(MovableInterface::class);
        $movableMock->method('getVelocity')
            ->willReturn(new Vector(0, 0));

        $rotatableMock = self::createMock(RotatableInterface::class);
        $rotatableMock->method('getDirection')
            ->willReturn(0);
        $rotatableMock->method('getAngularVelocity')
            ->willReturn(90);

        $changeVelocityCommand = new ChangeVelocityCommand($rotatableMock, $movableMock);

        self::expectException(CommandException::class);
        self::expectExceptionMessage('Cannot change velocity of a stationary object.');

        $changeVelocityCommand->execute();
    }

    /**
     * Не ожидается исключение CommandException.
     *
     * @throws CommandException
     */
    public function testExecuteChangesVelocitySuccessfully(): void
    {
        $movableMock = self::createMock(MovableInterface::class);

        $initialVelocity = new Vector(1, 0);
        $movableMock->expects(self::exactly(2))
            ->method('getVelocity')
            ->willReturn($initialVelocity);

        $rotatableMock = self::createMock(RotatableInterface::class);

        $rotatableMock->expects(self::once())
            ->method('getDirection')
            ->willReturn(0);
        $rotatableMock->expects(self::once())
            ->method('getAngularVelocity')
            ->willReturn(90);

        // Ожидаем, что метод setDirection будет вызван с новым значением
        $rotatableMock->expects(self::once())
            ->method('setDirection')
            ->with(self::equalTo(90));

        $changeVelocityCommand = new ChangeVelocityCommand($rotatableMock, $movableMock);

        // Ожидаем вызов метода setVelocity с новым вектором скорости
        $movableMock->expects(self::once())
            ->method('setVelocity')
            ->with(self::equalTo(new Vector(0, 1)));

        $changeVelocityCommand->execute();

        self::assertTrue(true);
    }
}