<?php

namespace Karion\Ssu\Tests\Points;

use Karion\Ssu\Points\BoosterPoints;
use Karion\Ssu\Points\PointsInWallet;
use Karion\Ssu\Points\StandardPoints;
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

    public function testCountingPointsWithoutExpire()
    {
        $now = new \DateTimeImmutable('now');
        $notExpirePoint = new StandardPoints(10);

        $pointsInWallet = new PointsInWallet(self::ACTION_ID, $notExpirePoint, $now);

        $this->assertEquals(0, $pointsInWallet->getAmount($now->modify('-1days')));
        $this->assertEquals(10, $pointsInWallet->getAmount($now));
        $this->assertEquals(10, $pointsInWallet->getAmount($now->modify('+9days')));
        $this->assertEquals(10, $pointsInWallet->getAmount($now->modify('+10days')));
        $this->assertEquals(10, $pointsInWallet->getAmount($now->modify('+11days')));
    }

    public function testPayOutStandardPoints()
    {
        $now = new \DateTimeImmutable('now');
        $notExpirePoint = new StandardPoints(10);

        $pointsInWallet = new PointsInWallet(self::ACTION_ID, $notExpirePoint, $now);

        $pointsInWallet->payOut($now->modify('+10days'));

        $this->assertEquals(0, $pointsInWallet->getAmount($now->modify('-1days')));
        $this->assertEquals(10, $pointsInWallet->getAmount($now));
        $this->assertEquals(10, $pointsInWallet->getAmount($now->modify('+9days')));
        $this->assertEquals(10, $pointsInWallet->getAmount($now->modify('+10days')));
        $this->assertEquals(0, $pointsInWallet->getAmount($now->modify('+11days')));
    }

    public function testPayOutBoosterPoints()
    {
        $now = new \DateTimeImmutable('now');
        $expirePoint = new BoosterPoints(10, $now->modify('+10days'));

        $pointsInWallet = new PointsInWallet(self::ACTION_ID, $expirePoint, $now);

        $pointsInWallet->payOut($now->modify('+9days'));

        $this->assertEquals(0, $pointsInWallet->getAmount($now->modify('-1days')));
        $this->assertEquals(10, $pointsInWallet->getAmount($now));
        $this->assertEquals(10, $pointsInWallet->getAmount($now->modify('+9days')));
        $this->assertEquals(0, $pointsInWallet->getAmount($now->modify('+10days')));
    }

    public function testPayOutExceptionBeforeStart()
    {
        $now = new \DateTimeImmutable('now');
        $notExpirePoint = new StandardPoints(10);

        $pointsInWallet = new PointsInWallet(self::ACTION_ID, $notExpirePoint, $now);

        $this->assertFalse($pointsInWallet->isPaidOut());

        $this->expectException(\InvalidArgumentException::class);
        $pointsInWallet->payOut($now->modify('-1days'));

        $this->assertFalse($pointsInWallet->isPaidOut());
    }

    public function testSecondPayOutException()
    {
        $now = new \DateTimeImmutable('now');
        $notExpirePoint = new StandardPoints(10);

        $pointsInWallet = new PointsInWallet(self::ACTION_ID, $notExpirePoint, $now);
        $this->assertFalse($pointsInWallet->isPaidOut());

        $pointsInWallet->payOut($now->modify('+1days'));
        $this->assertTrue($pointsInWallet->isPaidOut());

        $this->expectException(\InvalidArgumentException::class);
        $pointsInWallet->payOut($now->modify('+1days'));

        $this->assertTrue($pointsInWallet->isPaidOut());
    }
}
