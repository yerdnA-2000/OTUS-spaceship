<?php

namespace App\Rotate;

use App\Command\RetryableCommandInterface;
use App\Command\RetryableCommandTrait;
use Exception;

class Rotate implements RetryableCommandInterface
{
    use RetryableCommandTrait;

    private RotatableInterface $rotatable;

    public function __construct(RotatableInterface $rotatable)
    {
        $this->rotatable = $rotatable;
    }

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $this->checkRotatable();

        $this->rotatable->setDirection(
            ($this->rotatable->getDirection() + $this->rotatable->getAngularVelocity()) % 360
        );
    }

    /**
     * @throws Exception
     */
    private function checkRotatable(): void
    {
        if (null === $this->rotatable->getDirection()) {
            throw new Exception('Unable to determine direction.');
        }
        if (null === $this->rotatable->getAngularVelocity()) {
            throw new Exception('Unable to determine angular velocity.');
        }
    }
}