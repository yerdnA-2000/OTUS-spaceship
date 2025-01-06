<?php

namespace App\Command;

interface RetryableCommandInterface extends CommandInterface
{
    public function getRetryCount(): int;

    public function increaseRetryCount(int $value = 1): void;
}