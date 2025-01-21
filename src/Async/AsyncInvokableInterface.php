<?php

namespace App\Async;

interface AsyncInvokableInterface
{
    public function __invoke(): void;
}