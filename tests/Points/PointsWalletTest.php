<?php

namespace Karion\Ssu\Tests\Points;

use Karion\Ssu\Points\BoosterPoints;
use Karion\Ssu\Points\PointsWallet;
use Karion\Ssu\Points\StandardPoints;
use PHPUnit\Framework\TestCase;

class PointsWalletTest extends TestCase
{
    private const USER_ID = 1;

    public function testGetAmount()
    {
        $now = new \DateTimeImmutable();

        $wallet = new PointsWallet(self::USER_ID, $now);

        $wallet->addPointsFromAction(new StandardPoints(10), $now, 1);
        $wallet->addPointsFromAction(new BoosterPoints(10, $now->modify('+15days')), $now, 2);
        $wallet->addPointsFromAction(new BoosterPoints(10, $now->modify('+20days')), $now, 3);

        $this->assertEquals(0, $wallet->getAmount($now->modify('-1days')));
        $this->assertEquals(30, $wallet->getAmount($now));
        $this->assertEquals(30, $wallet->getAmount($now->modify('+10days')));
        $this->assertEquals(30, $wallet->getAmount($now->modify('+15days')));
        $this->assertEquals(20, $wallet->getAmount($now->modify('+20days')));
        $this->assertEquals(10, $wallet->getAmount($now->modify('+25days')));

        $this->assertEquals(10, $wallet->payOutAllPoints($now->modify('+25days')));
        $this->assertEquals(10, $wallet->getAmount($now->modify('+25days')));
        $this->assertEquals(0, $wallet->getAmount($now->modify('+26days')));
    }


}
