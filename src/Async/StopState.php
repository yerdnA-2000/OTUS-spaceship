<?php

namespace App\Async;

class StopState
{
    public function __construct(
        protected bool $hardStopped = false,
        protected bool $softStopped = false,
    ) {
    }

    public function hardStop(): void
    {
        $this->hardStopped = true;
    }

    public function softStop(): void
    {
        if ($this->hardStopped) {
            return;
        }
        $this->softStopped = true;
    }

    public function isHardStopped(): bool
    {
        return $this->hardStopped;
    }

    public function isSoftStopped(): bool
    {
        return $this->softStopped;
    }
}