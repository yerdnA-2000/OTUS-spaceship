<?php

namespace App\Command;

trait RetryableCommandTrait
{
    protected int $retryCount = 0;

    public function getRetryCount(): int
    {
        return $this->retryCount;
    }

    public function increaseRetryCount(int $value = 1): void
    {
        $this->retryCount += $value;
    }
}