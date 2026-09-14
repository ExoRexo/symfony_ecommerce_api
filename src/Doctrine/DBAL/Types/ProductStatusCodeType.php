<?php

namespace App\Doctrine\DBAL\Types;

use App\Enum\ProductStatusCode;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class ProductStatusCodeType extends StringType
{
    public const NAME = 'product_status_code';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof ProductStatusCode) {
            return $value->value;
        }

        return ProductStatusCode::tryFrom((string) $value)?->value ?? (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        return ProductStatusCode::from((string) $value);
    }
}
