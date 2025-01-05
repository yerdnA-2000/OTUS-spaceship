<?php

namespace App\Rotate;

class RotatingObject implements RotatableInterface
{
    protected ?int $direction;
    protected ?int $angularVelocity;

    public function __construct(int $direction = null, int $angularVelocity = null)
    {
        $this->direction = $direction;
        $this->angularVelocity = $angularVelocity;
    }

    public function getDirection(): ?int
    {
        return $this->direction;
    }

    public function setDirection(int $direction): void
    {
        $this->direction = $direction;
    }

    public function getAngularVelocity(): ?int
    {
        return $this->angularVelocity;
    }
}