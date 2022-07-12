<?php

namespace Karion\Ssu\Tests\Points;

use Karion\Ssu\Points\StandardPoints;
use PHPUnit\Framework\TestCase;

class StandardPointsTest extends TestCase
{
    public function testAmountIsConstantInTime()
    {
        $points = new StandardPoints(10);

        $this->assertEquals(10, $points->getAmountFor(new \DateTimeImmutable('now')));
        $this->assertEquals(10, $points->getAmountFor(new \DateTimeImmutable('tomorrow')));
        $this->assertEquals(10, $points->getAmountFor(new \DateTimeImmutable('+1000days')));
    }
}
