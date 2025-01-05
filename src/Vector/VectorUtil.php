<?php

namespace App\Vector;

class VectorUtil
{
    public static function sum(Vector $x, Vector $y): Vector
    {
        [$xp, $yp] = $x->getCoordinates();
        [$xd, $yd] = $y->getCoordinates();

        return new Vector(
            $xp + $xd,
            $yp + $yd,
        );
    }
}