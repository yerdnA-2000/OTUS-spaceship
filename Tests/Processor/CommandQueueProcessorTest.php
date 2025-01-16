<?php

namespace App\Tests\Processor;

use App\Command\Queue\CommandQueue;
use App\Command\RetryableCommandInterface;
use App\Config\Config;
use App\Processor\CommandQueueProcessor;
use App\Tests\TestCase\LoggingExceptionTestTrait;
use Exception;
use PHPUnit\Framework\TestCase;

class CommandQueueProcessorTest extends TestCase
{
    use LoggingExceptionTestTrait;

    public function testLoggingExceptionFailure(): void
    {
        $moveCommand = self::createMock(RetryableCommandInterface::class);
        $moveCommand->expects(self::exactly(4))
            ->method('execute')
            ->will($this->throwException(new Exception('Command failed')));
        $moveCommand->expects(self::exactly(4))
            ->method('getRetryCount')
            ->willReturnOnConsecutiveCalls(0, 1, 2, 3);

        // Создаем очередь команд
        $commandQueue = new CommandQueue();
        $commandQueue->add($moveCommand);

        $processor = new CommandQueueProcessor($commandQueue);
        $processor->process();

        $logFile = Config::EXCEPTION_LOGS_PATH;
        $this->assertFileExists($logFile);

        // Читаем содержимое файла и проверяем наличие сообщения
        $logContents = file_get_contents($logFile);
        $this->assertStringContainsString('Command failed', $logContents);
    }

    public function testSuccess(): void
    {
        $moveCommand = self::createMock(RetryableCommandInterface::class);

        // Создаем очередь команд
        $commandQueue = new CommandQueue();
        $commandQueue->add($moveCommand);

        $processor = new CommandQueueProcessor($commandQueue);
        $processor->process();

        $this->assertFileDoesNotExist(Config::EXCEPTION_LOGS_PATH);
    }
}