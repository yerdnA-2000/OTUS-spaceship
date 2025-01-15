<?php

namespace App\Plugin;

use App\Container\IoC;
use App\Generator\AdapterGenerator;

class AdapterPlugin implements PluginInterface
{

    public function load(): void
    {
        IoC::resolve('ioc.register', 'Adapter', function ($interfaceName, $obj) {
            return AdapterGenerator::createAdapter($interfaceName, $obj);
        })->execute();
    }
}