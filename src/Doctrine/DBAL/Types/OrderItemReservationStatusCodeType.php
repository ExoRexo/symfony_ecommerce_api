<?php

namespace App\Doctrine\DBAL\Types;

use App\Enum\OrderItemReservationStatusCode;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class OrderItemReservationStatusCodeType extends StringType
{
    public const NAME = 'order_item_reservation_status_code';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof OrderItemReservationStatusCode) {
            return $value->value;
        }

        return OrderItemReservationStatusCode::tryFrom((string) $value)?->value ?? (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        return OrderItemReservationStatusCode::from((string) $value);
    }
}
