<?php

namespace App\Tests\Command;

use App\Command\CommandInterface;
use App\Command\MacroCommand;
use App\Command\Queue\CommandQueue;
use App\Exception\CommandException;
use Exception;
use PHPUnit\Framework\TestCase;

class MacroCommandTest extends TestCase
{
    /**
     * Не ожидается исключение CommandException.
     *
     * @throws CommandException
     */
    public function testExecuteRunsAllCommandsSuccessfully(): void
    {
        $command1 = self::createMock(CommandInterface::class);
        $command1->expects(self::once())
            ->method('execute');

        $command2 = self::createMock(CommandInterface::class);
        $command2->expects(self::once())
            ->method('execute');

        $macroCommand = new MacroCommand(new CommandQueue([$command1, $command2]));
        $macroCommand->execute();

        self::assertTrue(true);
    }

    /**
     * Ожидается исключение CommandException.
     */
    public function testExecuteThrowsExceptionOnFirstFailure(): void
    {
        $command1 = self::createMock(CommandInterface::class);
        $command1->expects(self::once())
            ->method('execute')
            ->will(self::throwException(new Exception('First command failed')));

        $command2 = self::createMock(CommandInterface::class);

        $macroCommand = new MacroCommand(new CommandQueue([$command1, $command2]));

        self::expectException(CommandException::class);
        self::expectExceptionMessage('Macro command execution failed: First command failed');

        $macroCommand->execute();
    }

    /**
     * Ожидается исключение CommandException.
     */
    public function testExecuteThrowsExceptionOnSecondFailure(): void
    {
        $command1 = self::createMock(CommandInterface::class);
        $command1->expects(self::once())->method('execute');

        $command2 = self::createMock(CommandInterface::class);
        $command2->expects(self::once())
            ->method('execute')
            ->will(self::throwException(new Exception('Second command failed')));

        $macroCommand = new MacroCommand(new CommandQueue([$command1, $command2]));

        self::expectException(CommandException::class);
        self::expectExceptionMessage('Macro command execution failed: Second command failed');

        $macroCommand->execute();
    }
}