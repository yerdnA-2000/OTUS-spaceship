<?php

namespace App\Command;

use App\Exception\CommandException;
use App\Move\MovableInterface;
use App\Rotate\RotatableInterface;
use App\Vector\Vector;

/**
 * Изменяет вектор мгновенной скорости объекта при повороте.
 */
class ChangeVelocityCommand implements CommandInterface
{
    private RotatableInterface $rotatable;
    private MovableInterface $movable;

    public function __construct(RotatableInterface $rotatable, MovableInterface $movable)
    {
        $this->rotatable = $rotatable;
        $this->movable = $movable;
    }

    /**
     * @throws CommandException
     */
    public function execute(): void
    {
        if ($this->isStationary()) {
            throw new CommandException('Cannot change velocity of a stationary object.');
        }

        // Получаем текущее направление и угловую скорость
        $currentDirection = $this->rotatable->getDirection();
        $angularVelocity = $this->rotatable->getAngularVelocity();

        // Вычисляем новое направление
        $newDirection = ($currentDirection + $angularVelocity) % 360;

        // Устанавливаем новое направление
        $this->rotatable->setDirection($newDirection);

        // Изменяем вектор скорости на основе нового направления
        $currentVelocity = $this->movable->getVelocity()->getCoordinates();
        $newVelocity = $this->rotateVector($currentVelocity, $angularVelocity);

        // Устанавливаем новый вектор скорости
        $this->movable->setVelocity(new Vector($newVelocity[0], $newVelocity[1]));
    }

    /**
     * Применяет матрицу вращения для изменения вектора на заданный угол.
     */
    private function rotateVector(array $vector, int $angle): array
    {
        // Угол в радианы
        $radians = deg2rad($angle);

        // Применяем матрицу вращения
        return [
            (int)($vector[0] * cos($radians) - $vector[1] * sin($radians)),
            (int)($vector[0] * sin($radians) + $vector[1] * cos($radians))
        ];
    }

    /**
     * Проверяем, движется ли объект.
     */
    private function isStationary(): bool
    {
        return $this->movable->getVelocity()->getCoordinates() === [0, 0];
    }
}