<?php

namespace App\Command;

use App\Command\Queue\CommandQueue;
use App\Exception\CommandException;
use App\Move\MoveCommand;

class MoveStraightCommand implements CommandInterface
{
    private FuelTankInterface $fuelTank;
    private float $fuelConsumption;
    private MoveCommand $moveCommand;

    public function __construct(FuelTankInterface $fuelTank, MoveCommand $moveCommand, float $fuelConsumption)
    {
        $this->fuelTank = $fuelTank;
        $this->moveCommand = $moveCommand;
        $this->fuelConsumption = $fuelConsumption;
    }

    /**
     * @throws CommandException
     */
    public function execute(): void
    {
        $macroCommand = new MacroCommand($this->createCommandQueue());

        $macroCommand->execute();
    }

    /**
     * @return CommandQueue
     */
    private function createCommandQueue(): CommandQueue
    {
        return new CommandQueue([
            new CheckFuelCommand($this->fuelTank),
            $this->moveCommand,
            new BurnFuelCommand($this->fuelTank, $this->fuelConsumption),
        ]);
    }
}