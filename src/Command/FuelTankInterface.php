<?php

namespace App\Command;

interface FuelTankInterface
{
    public function hasEnoughFuel(): bool;

    /**
     * Метод для уменьшения уровня топлива
     */
    public function burn(float $amount): void;
}