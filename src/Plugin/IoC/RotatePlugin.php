<?php

namespace App\Plugin\IoC;

use App\Container\IoC;
use App\Rotate\RotatableInterface;
use App\Rotate\RotateCommand;

class RotatePlugin implements IoCPluginInterface
{

    public function register(IoC $ioc): void
    {
        $ioc->register('rotate', function (RotatableInterface $rotatable) {
            return new RotateCommand($rotatable);
        });
    }
}