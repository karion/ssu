<?php
declare(strict_types=1);

namespace Karion\Ssu\Points;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;

class PointType extends JsonType
{
    private const BOOSTERS = 'boosters';
    private const POINTS = 'points';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        switch (get_class($value)) {
            case StandardPoints::class:
                return parent::convertToDatabaseValue(
                    [
                        'type' => self::POINTS,
                        'amount' => $value->getAmountFor(new \DateTimeImmutable('0000-01-01'))
                    ],
                    $platform
                );

            case BoosterPoints::class:
                return parent::convertToDatabaseValue(
                    [
                        'type' => self::POINTS,
                        'amount' => $value->getAmountFor(new \DateTimeImmutable('0000-01-01')),
                        'whenExpire' => $value->getWhenExpires()->format('Y-m-d H:i:s')
                    ],
                    $platform
                );
            default:
                throw ConversionException::conversionFailedInvalidType($value, 'point_type', [Point::class]);
        }
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $array = parent::convertToPHPValue($value, $platform);

        if(!isset($array['type'])) {
            throw ConversionException::conversionFailed($value, Point::class);
        }

        switch ($array['type']) {
            case self::POINTS:
                return new StandardPoints($array['amount']);
            case self::BOOSTERS:
                return new BoosterPoints(
                    $array['amount'],
                    new \DateTimeImmutable($array['whenExpire'])
                );
        }

        throw ConversionException::conversionFailed($value, Point::class);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
