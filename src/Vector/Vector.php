<?php

namespace App\Vector;

class Vector implements VectorInterface
{
    /**
     * @var int[]
     */
    private array $coordinates = [];

    public function __construct(int $x, int $y)
    {
        $this->coordinates = [$x, $y];
    }



    public function getCoordinates(): array
    {
        return $this->coordinates;
    }
}