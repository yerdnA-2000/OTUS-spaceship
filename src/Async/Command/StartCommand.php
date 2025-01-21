<?php

namespace App\Async\Command;

use App\Async\AsyncInvokableInterface;
use App\Command\CommandInterface;
use Spatie\Async\Pool;

class StartCommand implements CommandInterface
{

    public function __construct(protected Pool $pool, protected AsyncInvokableInterface $asyncInvokable)
    {
    }

    public function execute(): void
    {
        $this->pool[] = $this->asyncInvokable;
    }
}