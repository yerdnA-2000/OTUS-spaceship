<?php

namespace App\Plugin\IoC;

use App\Command\FuelTankInterface;
use App\Command\MoveStraightCommand;
use App\Container\IoC;
use App\Move\MoveCommand;

class MovePlugin implements IoCPluginInterface
{

    public function register(IoC $ioc): void
    {
        $ioc->register('moveStraight', function (FuelTankInterface $fuelTank, MoveCommand $moveCommand, float $fuelConsumption) {
            return new MoveStraightCommand($fuelTank, $moveCommand, $fuelConsumption);
        });
    }
}