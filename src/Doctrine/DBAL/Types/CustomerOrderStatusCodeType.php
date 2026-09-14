<?php

namespace App\Doctrine\DBAL\Types;

use App\Enum\CustomerOrderStatusCode;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class CustomerOrderStatusCodeType extends StringType
{
    public const string NAME = 'customer_order_status_code';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof CustomerOrderStatusCode) {
            return $value->value;
        }

        return CustomerOrderStatusCode::tryFrom((string) $value)?->value ?? (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CustomerOrderStatusCode
    {
        if ($value === null || $value === '') {
            return null;
        }

        return CustomerOrderStatusCode::from((string) $value);
    }
}
