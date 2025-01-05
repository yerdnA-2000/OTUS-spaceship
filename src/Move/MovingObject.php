<?php

namespace App\Move;

use App\Vector\Vector;

class MovingObject implements MovableInterface
{
    protected ?Vector $position = null;
    protected ?Vector $velocity = null;

    public function __construct(Vector $position = null, Vector $velocity = null)
    {
        $this->position = $position;
        $this->velocity = $velocity;
    }

    public function getPosition(): ?Vector
    {
        return $this->position;
    }

    public function getVelocity(): ?Vector
    {
        return $this->velocity;
    }

    public function setPosition(Vector $position): void
    {
        $this->position = $position;
    }

    public function setVelocity(Vector $velocity): void
    {
        $this->velocity = $velocity;
    }
}