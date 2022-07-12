<?php

namespace Karion\Ssu\Points;

interface Point
{
    public function getAmountFor(\DateTimeImmutable $when): int;
}