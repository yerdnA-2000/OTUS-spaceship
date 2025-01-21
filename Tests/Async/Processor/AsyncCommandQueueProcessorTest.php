<?php

namespace App\Tests\Async\Processor;

use App\Async\Command\HardStopCommand;
use App\Async\Command\SoftStopCommand;
use App\Async\Command\StartCommand;
use App\Async\Processor\AsyncCommandQueueProcessor;
use App\Async\StopState;
use App\Command\Queue\CommandSplQueue;
use App\Tests\Stub\LoggingInfoCommandStub;
use App\Tests\TestCase\LoggingInfoTestTrait;
use PHPUnit\Framework\TestCase;
use Spatie\Async\Pool;

class AsyncCommandQueueProcessorTest extends TestCase
{
    use LoggingInfoTestTrait;

    public function testProcessHardStopCase(): void
    {
        $stopState = new StopState();

        // Логгер для вывода информации о том, какие команды LoggingInfoCommandStub были выполнены
        $logger = $this->createLogger();

        $hardStopCmd = new HardStopCommand($stopState);
        $cmd1 = new LoggingInfoCommandStub('First', $logger);
        $cmd2 = new LoggingInfoCommandStub('Second', $logger);

        $queue = new CommandSplQueue([
            $cmd1,
            $hardStopCmd,
            $cmd2,
        ]);

        $processor = new AsyncCommandQueueProcessor($queue, $stopState);

        $pool = Pool::create();

        (new StartCommand($pool, $processor))->execute();

        await($pool);

        $logContents = $this->getInfoLogContents();

        // Проверяем в логах, что выполнена первая команда, идущая перед hard-stop командой
        self::assertStringContainsString("Command {$cmd1->getName()} executed", $logContents);

        // Проверяем в логах, что НЕ выполнена вторая команда, идущая после hard-stop команды
        self::assertStringNotContainsString("Command {$cmd2->getName()} executed", $logContents);
    }

    public function testProcessSoftStopCase(): void
    {
        $stopState = new StopState();

        $logger = $this->createLogger();

        $softStopCmd = new SoftStopCommand($stopState);
        $cmd1 = new LoggingInfoCommandStub('First', $logger);
        $cmd2 = new LoggingInfoCommandStub('Second', $logger);

        $queue = new CommandSplQueue([
            $cmd1,
            $softStopCmd,
            $cmd2,
        ]);

        $processor = new AsyncCommandQueueProcessor($queue, $stopState);

        $pool = Pool::create();

        (new StartCommand($pool, $processor))->execute();

        await($pool);

        $logContents = $this->getInfoLogContents();

        // Проверяем в логах, что все команды выполнены
        self::assertStringContainsString("Command {$cmd1->getName()} executed", $logContents);
        self::assertStringContainsString("Command {$cmd2->getName()} executed", $logContents);
    }
}