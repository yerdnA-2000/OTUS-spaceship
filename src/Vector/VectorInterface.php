<?php

namespace App\Vector;

interface VectorInterface
{
    /**
     * @return int[]
     */
    public function getCoordinates(): array;
}