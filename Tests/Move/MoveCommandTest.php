<?php

namespace App\Tests\Move;

use App\Move\MoveCommand;
use App\Move\MovingObject;
use App\Vector\Vector;
use Exception;
use PHPUnit\Framework\TestCase;

class MoveCommandTest extends TestCase
{
    /**
     * Для объекта, находящегося в точке (12, 5) и движущегося со скоростью (-7, 3) движение меняет положение объекта на (5, 8)
     */
    public function testMoving(): void
    {
        $movingObj = new MovingObject(
            new Vector(12, 5),
            new Vector(-7, 3),
        );

        $move = new MoveCommand($movingObj);
        $move->execute();

        self::assertEquals([5, 8], $movingObj->getPosition()->getCoordinates());
    }

    /**
     * Попытка сдвинуть объект, у которого невозможно прочитать положение в пространстве, приводит к ошибке.
     */
    public function testWrongPosition(): void
    {
        $movingObj = new MovingObject(velocity: new Vector(1, 1));
        $move = new MoveCommand($movingObj);

        self::expectException(Exception::class);
        self::expectExceptionMessage('Unable to determine position.');

        $move->execute();
    }

    /**
     * Попытка сдвинуть объект, у которого невозможно прочитать значение мгновенной скорости, приводит к ошибке
     */
    public function testWrongVelocity(): void
    {
        $movingObj = new MovingObject(position: new Vector(1, 1));
        $move = new MoveCommand($movingObj);

        self::expectException(Exception::class);
        self::expectExceptionMessage('Unable to determine velocity.');

        $move->execute();
    }
}