<?php

namespace Karion\Ssu\Tests\Points;

use Karion\Ssu\Points\BoosterPoints;
use Karion\Ssu\Points\PointsInWallet;
use PHPUnit\Framework\TestCase;

class PointsInWalletTest extends TestCase
{
    private const ACTION_ID = 1;

    public function testCountingPointsWithExpire()
    {
        $now = new \DateTimeImmutable('now');
        $expirePoint = new BoosterPoints(10, $now->modify('+10days'));

        $pointsInWallet = new PointsInWallet(self::ACTION_ID, $expirePoint, $now);



        $this->assertEquals(0, $pointsInWallet->getAmount($now->modify('-1days')));
        $this->assertEquals(10, $pointsInWallet->getAmount($now));
        $this->assertEquals(10, $pointsInWallet->getAmount($now->modify('+9days')));
        $this->assertEquals(10, $pointsInWallet->getAmount($now->modify('+10days')));
        $this->assertEquals(0, $pointsInWallet->getAmount($now->modify('+11days')));
    }

}
