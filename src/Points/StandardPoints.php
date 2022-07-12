<?php

namespace Karion\Ssu\Points;

class StandardPoints implements Point
{
    public function __construct(
        private int $amount
    ) {}

    public function getAmountFor(\DateTimeImmutable $when): int
    {
        return $this->amount;
    }
}