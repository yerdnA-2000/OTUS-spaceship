<?php

namespace App\Tests\TestCase;

use App\Config\Config;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

trait LoggingInfoTestTrait
{
    /**
     * Удаляем временный файл лога после каждого теста
     */
    protected function tearDown(): void
    {
        $this->removeInfoLogFile();
    }

    private function removeInfoLogFile(): void
    {
        if (file_exists($logFile = Config::TEST_INFO_LOGS_PATH)) {
            unlink($logFile);
        }
    }

    private function createLogger(): Logger
    {
        $logger = new Logger('test');
        $logger->pushHandler(new StreamHandler(Config::TEST_INFO_LOGS_PATH, Level::Info->value));

        return $logger;
    }

    private function getInfoLogContents(): bool|string
    {
        $logFile = Config::TEST_INFO_LOGS_PATH;
        $this->assertFileExists($logFile);

        return file_get_contents($logFile);
    }
}