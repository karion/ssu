<?php
declare(strict_types=1);

namespace Karion\Ssu\Points;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Karion\Ssu\Common\BaseEntity;

#[Entity]
class PointsWallet extends BaseEntity
{
    #[Column(type: 'integer')]
    private int $userId;

    #[OneToMany(mappedBy: 'account', targetEntity: PointsInWallet::class, cascade: ['all'])]
    private Collection $points;

    public function __construct(int $userId, \DateTimeImmutable $when)
    {
        $this->userId = $userId;
        $this->points = new ArrayCollection();
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function addPointsFromAction(Point $point, \DateTimeImmutable $when, ?int $actionId = null)
    {
        $this->points->add(new PointsInWallet($actionId, $point, $when));
    }

    public function getAmount(\DateTimeImmutable $when): int
    {
        return array_sum(
            $this->points
                ->map(fn(PointsInWallet $pointsInWallet) => $pointsInWallet->getAmount($when))
                ->toArray()
        );
    }

    public function payOutAllPoints(\DateTimeImmutable $when): int
    {
        $amountBeforePayOut = $this->getAmount($when);

        $this->points
            ->filter(fn(PointsInWallet $pointsInWallet) => !$pointsInWallet->isPaidOut())
            ->map(fn(PointsInWallet $pointsInWallet) => $pointsInWallet->payOut($when))
        ;

        return $amountBeforePayOut;
    }
}