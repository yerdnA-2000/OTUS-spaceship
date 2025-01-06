<?php

namespace App\Command;

use App\Exception\CommandException;

class BurnFuelCommand implements CommandInterface
{
    private FuelTankInterface $fuelTank;

    /** Кол-во топлива, которое будет израсходовано */
    private float $fuelConsumption;

    public function __construct(FuelTankInterface $fuelTank, float $fuelConsumption)
    {
        $this->fuelTank = $fuelTank;
        $this->fuelConsumption = $fuelConsumption;
    }

    /**
     * @throws CommandException
     */
    public function execute(): void
    {
        if (!$this->fuelTank->hasEnoughFuel()) {
            throw new CommandException('Not enough fuel to burn.');
        }

        $this->fuelTank->burn($this->fuelConsumption);
    }
}