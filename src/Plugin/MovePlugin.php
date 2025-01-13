<?php

namespace App\Plugin;

use App\Command\FuelTankInterface;
use App\Command\MoveStraightCommand;
use App\Container\IoC;
use App\Exception\IoCException;
use App\Move\MoveCommand;

class MovePlugin implements PluginInterface
{
    /**
     * @throws IoCException
     */
    public function load(): void
    {
        IoC::resolve(
            'ioc.register',
            'moveStraight',
            function (FuelTankInterface $fuelTank, MoveCommand $moveCommand, float $fuelConsumption) {
                return new MoveStraightCommand($fuelTank, $moveCommand, $fuelConsumption);
            }
        )->execute();
    }
}