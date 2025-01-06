<?php

namespace App\Tests\Command;

use App\Command\LogExceptionCommand;
use App\Config\Config;
use App\Tests\TestCase\LoggingExceptionTestTrait;
use PHPUnit\Framework\TestCase;

class LogExceptionCommandTest extends TestCase
{
    use LoggingExceptionTestTrait;

    public function testLogException(): void
    {
        $logCommand = new LogExceptionCommand('Test exception message');
        $logCommand->execute();

        $logFile = Config::EXCEPTION_LOGS_PATH;
        $this->assertFileExists($logFile);

        $logContents = file_get_contents($logFile);
        $this->assertStringContainsString('Test exception message', $logContents);
    }
}