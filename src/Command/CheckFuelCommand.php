<?php

namespace App\Command;

use App\Exception\CommandException;

class CheckFuelCommand implements CommandInterface
{
    private FuelTankInterface $fuelTank;

    public function __construct(FuelTankInterface $fuelTank)
    {
        $this->fuelTank = $fuelTank;
    }

    /**
     * @throws CommandException
     */
    public function execute(): void
    {
        if (!$this->fuelTank->hasEnoughFuel()) {
            throw new CommandException('Not enough fuel to execute the command.');
        }
    }
}