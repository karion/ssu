<?php

namespace Karion\Ssu\Points;

class BoosterPoints implements Point
{
    private int $amount;
    private \DateTimeImmutable $whenExpires;

    public function __construct(int $amount, \DateTimeImmutable $whenExpires)
    {
        $this->amount = $amount;
        $this->whenExpires = $whenExpires;
    }

    public function getAmountFor(\DateTimeImmutable $when): int
    {
        if ($when > $this->whenExpires) {
            return 0;
        }

        return $this->amount;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getWhenExpires(): \DateTimeImmutable
    {
        return $this->whenExpires;
    }
}
