<?php

declare(strict_types=1);

namespace Karion\Ssu\Common;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Version;

abstract class BaseEntity
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    protected int $id;

    public function getId(): int
    {
        return $this->id;
    }
}
