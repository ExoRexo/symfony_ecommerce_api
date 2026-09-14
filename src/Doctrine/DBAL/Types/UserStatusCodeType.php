<?php

namespace App\Doctrine\DBAL\Types;

use App\Enum\UserStatusCode;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class UserStatusCodeType extends StringType
{
    public const NAME = 'user_status_code';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof UserStatusCode) {
            return $value->value;
        }

        return UserStatusCode::tryFrom((string) $value)?->value ?? (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        return UserStatusCode::from((string) $value);
    }
}
