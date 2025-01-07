<?php

namespace App\Plugin\IoC;

use App\Container\IoC;

interface IoCPluginInterface
{
    public function register(IoC $ioc): void;
}