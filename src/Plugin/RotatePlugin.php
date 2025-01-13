<?php

namespace App\Plugin;

use App\Container\IoC;
use App\Exception\IoCException;
use App\Rotate\RotatableInterface;
use App\Rotate\RotateCommand;

class RotatePlugin implements PluginInterface
{
    /**
     * @throws IoCException
     */
    public function load(): void
    {
        IoC::resolve(
            'ioc.register',
            'rotate',
            function (RotatableInterface $rotatable) {
                return new RotateCommand($rotatable);
            }
        )->execute();
    }
}