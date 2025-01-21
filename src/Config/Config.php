<?php

namespace App\Config;

class Config
{
    public const LOGS_PATH = __DIR__ . '/../../var/logs';
    public const TEST_LOGS_PATH = __DIR__ . '/../../var/logs/test';
    public const EXCEPTION_LOGS_PATH = self::LOGS_PATH . '/exceptions.log';
    public const TEST_INFO_LOGS_PATH = self::TEST_LOGS_PATH . '/info.log';
}