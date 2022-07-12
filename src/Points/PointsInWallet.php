<?php

namespace Karion\Ssu\Points;

use Doctrine\ORM\Mapping\Column;
use Karion\Ssu\Common\BaseEntity;

class PointsInWallet extends BaseEntity
{
    #[Column(type: 'integer', nullable: true)]
    private ?int $actionId = null;

    #[Column(type: 'point')]
    private Point $points;

    #[Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $date;

    private ?\DateTimeImmutable $whenPaidOut = null;

    public function __construct(?int $actionId, Point $points, \DateTimeImmutable $date)
    {
        $this->actionId = $actionId;
        $this->points = $points;
        $this->date = $date;
    }

    public function getAmount(\DateTimeImmutable $when): int
    {

        if ($this->date > $when) {
            return 0;
        }


        if ($this->whenPaidOut !== null && $this->whenPaidOut < $when) {
            return 0;
        }

        return $this->points->getAmountFor($when);
    }

    public function isPaidOut(): bool
    {
        return $this->whenPaidOut !== null;
    }

    public function payOut(\DateTimeImmutable $when): void
    {
        if ($this->isPaidOut()) {
            throw new \InvalidArgumentException('Points already paid out.');
        }

        if ($when < $this->date) {
            throw new \InvalidArgumentException('Can`t pay out before start.');
        }

        $this->whenPaidOut = $when;
    }

    public function getActionId(): ?int
    {
        return $this->actionId;
    }
}
