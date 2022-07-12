<?php

namespace Karion\Ssu\Tests\Points;

use Karion\Ssu\Points\BoosterPoints;
use PHPUnit\Framework\TestCase;

class BoosterPointsTest extends TestCase
{
    public function testPointsExpire()
    {
        $now = new \DateTimeImmutable('now');

        $points = new BoosterPoints(10, $now->modify('+10 hours'));

        $this->assertEquals(10, $points->getAmountFor($now));
        $this->assertEquals(10, $points->getAmountFor($now->modify('+10 hours')));
        $this->assertEquals(0, $points->getAmountFor($now->modify('+11 hours')));
        $this->assertEquals(0, $points->getAmountFor($now->modify('+1000days')));
    }
}
