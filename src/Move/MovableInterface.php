<?php

namespace App\Move;

use App\Vector\Vector;

interface MovableInterface
{
    public function getPosition(): ?Vector;
    public function getVelocity(): ?Vector;

    public function setPosition(Vector $position): void;
    public function setVelocity(Vector $velocity): void;
}