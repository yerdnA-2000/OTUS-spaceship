<?php

namespace App\Tests\TestCase;

use App\Config\Config;

trait LoggingExceptionTestTrait
{
    /**
     * Удаляем временный файл лога после каждого теста
     */
    protected function tearDown(): void
    {
        $this->removeLogFile();
    }

    private function removeLogFile(): void
    {
        if (file_exists($logFile = Config::EXCEPTION_LOGS_PATH)) {
            unlink($logFile);
        }
    }
}