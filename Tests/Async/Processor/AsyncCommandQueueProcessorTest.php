<?php

namespace App\Tests\Async\Processor;

use App\Async\Command\HardStopCommand;
use App\Async\Command\MoveToCommand;
use App\Async\Command\RunCommand;
use App\Async\Command\SoftStopCommand;
use App\Async\Command\StartCommand;
use App\Async\Processor\AsyncCommandQueueProcessor;
use App\Async\State\NormalState;
use App\Async\State\SoftStopState;
use App\Command\Queue\CommandSplQueue;
use App\Tests\Stub\LoggingInfoCommandStub;
use App\Tests\Stub\LoggingStateCommandStub;
use App\Tests\TestCase\LoggingInfoTestTrait;
use PHPUnit\Framework\TestCase;
use Spatie\Async\Pool;

class AsyncCommandQueueProcessorTest extends TestCase
{
    use LoggingInfoTestTrait;

    public function testProcessHardStopCase(): void
    {
        $logger = $this->createLogger();

        $cmd1 = new LoggingInfoCommandStub('First', $logger);
        $cmd2 = new LoggingInfoCommandStub('Second', $logger);

        $queue = new CommandSplQueue([$cmd1]);

        $processor = new AsyncCommandQueueProcessor($queue);

        $hardStopCmd = new HardStopCommand($processor);
        $queue->enqueue($hardStopCmd);
        $queue->enqueue($cmd2);

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
        $logger = $this->createLogger();

        $cmd1 = new LoggingInfoCommandStub('First', $logger);
        $cmd2 = new LoggingInfoCommandStub('Second', $logger);

        $queue = new CommandSplQueue([$cmd1]);

        $processor = new AsyncCommandQueueProcessor($queue);

        $softStopCmd = new SoftStopCommand($processor);
        $queue->enqueue($softStopCmd);
        $queue->enqueue($cmd2);

        $pool = Pool::create();

        (new StartCommand($pool, $processor))->execute();

        await($pool);

        $logContents = $this->getInfoLogContents();

        // Проверяем в логах, что выполнена первая команда, идущая перед soft-stop командой
        self::assertStringContainsString("Command {$cmd1->getName()} executed", $logContents);

        // Проверяем в логах, что выполнена вторая команда, идущая после soft-stop команды
        self::assertStringContainsString("Command {$cmd2->getName()} executed", $logContents);
    }

    public function testProcessMoveToCommandCase(): void
    {
        $logger = $this->createLogger();

        $cmd1 = new LoggingInfoCommandStub('First', $logger);
        $cmd2 = new LoggingInfoCommandStub('Second', $logger);

        $fromQueue = new CommandSplQueue([$cmd1]);

        $processor = new AsyncCommandQueueProcessor($fromQueue);

        $toQueue = new CommandSplQueue();

        $moveToCmd = new MoveToCommand($processor, $toQueue, true);
        $fromQueue->enqueue($moveToCmd);
        $fromQueue->enqueue($cmd2);

        $pool = Pool::create()->forceSynchronous();

        (new StartCommand($pool, $processor))->execute();

        await($pool);

        $logContents = $this->getInfoLogContents();

        // Проверяем что очередь из которой перемещали команды ПУСТА
        self::assertTrue($fromQueue->isEmpty());

        // Проверяем что очередь в которую перемещали команды НЕ ПУСТА
        self::assertFalse($toQueue->isEmpty());

        // Проверяем в логах, что выполнена первая команда, идущая перед MoveTo командой
        self::assertStringContainsString("Command {$cmd1->getName()} executed", $logContents);

        // Проверяем в логах, что НЕ выполнена вторая команда, идущая после MoveTo команды (попала в другую очередь)
        self::assertStringNotContainsString("Command {$cmd2->getName()} executed", $logContents);
    }

    public function testProcessRunCommandCase(): void
    {
        $logger = $this->createLogger();

        $cmd1 = new LoggingInfoCommandStub('First', $logger);
        $cmd2 = new LoggingInfoCommandStub('Second', $logger);

        $queue = new CommandSplQueue([$cmd1]);

        $processor = new AsyncCommandQueueProcessor($queue);

        // Добавление SoftStopCommand и проверка, что после её выполнения state будет равен SoftStopState
        $softStopCmd = new SoftStopCommand($processor);
        $queue->enqueue($softStopCmd);
        $queue->enqueue(new LoggingStateCommandStub(SoftStopState::class, $logger, $processor));

        // Добавление RunCommand и проверка, что после её выполнения state будет равен NormalState
        $runCmd = new RunCommand($processor);
        $queue->enqueue($runCmd);
        $queue->enqueue(new LoggingStateCommandStub(NormalState::class, $logger, $processor));
        $queue->enqueue($cmd2);

        // Добавление HardStopCommand в конце, чтобы завершить работу.
        $hardStopCmd = new HardStopCommand($processor);
        $queue->enqueue($hardStopCmd);

        $pool = Pool::create();

        (new StartCommand($pool, $processor))->execute();

        await($pool);

        $logContents = $this->getInfoLogContents();

        // Проверяем в логах, что выполнена первая команда, идущая перед soft-stop командой
        self::assertStringContainsString("Command {$cmd1->getName()} executed", $logContents);

        // Проверяем в логах, что в процессе выполнения был state со значением SoftStopState
        self::assertStringContainsString("State is ".SoftStopState::class, $logContents);

        // Проверяем в логах, что в процессе выполнения был state со значением NormalState (RunCommand выполнена)
        self::assertStringContainsString("State is ".NormalState::class, $logContents);

        // Проверяем в логах, что выполнена вторая команда, идущая после RunCommand команды
        self::assertStringContainsString("Command {$cmd2->getName()} executed", $logContents);
    }
}