<?php

namespace App\Tests\Rotate;

use App\Rotate\Rotate;
use App\Rotate\RotatingObject;
use Exception;
use PHPUnit\Framework\TestCase;

class RotateTest extends TestCase
{
    /**
     * Для объекта, повернутого на угол 45гр, с угловой скоростью 90гр, поворот выполняется на угол 135гр от нулевого положения
     */
    public function testRotate(): void
    {
        $rotatable = new RotatingObject(
            direction: 45,
            angularVelocity: 90,
        );

        $rotate = new Rotate($rotatable);
        $rotate->execute();

        self::assertEquals(135, $rotatable->getDirection());
    }

    /**
     * Для объекта, повернутого на угол 90гр, с угловой скоростью 0гр, поворот не выполняется
     */
    public function testZeroAngularVelocity(): void
    {
        $rotatable = new RotatingObject(
            direction: 90,
            angularVelocity: 0,
        );

        $rotate = new Rotate($rotatable);
        $rotate->execute();

        self::assertEquals(90, $rotatable->getDirection());
    }

    /**
     * Попытка повернуть объект, у которого невозможно прочитать значение угла, на которое он повернут, приводит к ошибке
     */
    public function testWrongDirection(): void
    {
        $rotatable = new RotatingObject(
            angularVelocity: 70,
        );
        $rotate = new Rotate($rotatable);

        self::expectException(Exception::class);
        self::expectExceptionMessage('Unable to determine direction.');

        $rotate->execute();
    }

    /**
     * Попытка повернуть объект, у которого невозможно прочитать значение угловой скорости, приводит к ошибке
     */
    public function testWrongAngularVelocity(): void
    {
        $rotatable = new RotatingObject(
            direction: 100,
        );
        $rotate = new Rotate($rotatable);

        self::expectException(Exception::class);
        self::expectExceptionMessage('Unable to determine angular velocity.');

        $rotate->execute();
    }
}