<?php

namespace App\Rotate;

interface RotatableInterface
{
    public function getDirection(): ?int;
    public function getAngularVelocity(): ?int;

    public function setDirection(int $direction): void;
}