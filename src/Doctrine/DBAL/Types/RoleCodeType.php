<?php

namespace App\Doctrine\DBAL\Types;

use App\Enum\RoleCode;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class RoleCodeType extends StringType
{
    public const NAME = 'role_code';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof RoleCode) {
            return $value->value;
        }

        return RoleCode::tryFrom((string) $value)?->value ?? (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        return RoleCode::from((string) $value);
    }
}
