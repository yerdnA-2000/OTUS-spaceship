<?php

namespace App\Config;

class Config
{
    public const LOGS_PATH = __DIR__ . '/../../var/logs';
    public const EXCEPTION_LOGS_PATH = self::LOGS_PATH . '/exceptions.log';
}