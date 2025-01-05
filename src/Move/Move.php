<?php

namespace App\Move;

use App\Vector\VectorUtil;
use Exception;

class Move
{
    private MovableInterface $movable;

    public function __construct(MovableInterface $movable)
    {
        $this->movable = $movable;
    }

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $this->checkMovable();

        $position = VectorUtil::sum(
            $this->movable->getPosition(),
            $this->movable->getVelocity(),
        );

        $this->movable->setPosition($position);
    }

    /**
     * @throws Exception
     */
    private function checkMovable(): void
    {
        if (!$this->movable->getPosition()) {
            throw new Exception('Unable to determine position.');
        }
        if (!$this->movable->getVelocity()) {
            throw new Exception('Unable to determine velocity.');
        }
    }
}