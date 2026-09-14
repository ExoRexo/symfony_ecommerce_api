<?php

namespace App\Doctrine\DBAL\Types;

use App\Enum\CustomerWalletTransactionPurposeCode;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class CustomerWalletTransactionPurposeCodeType extends StringType
{
    public const NAME = 'customer_wallet_transaction_purpose_code';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof CustomerWalletTransactionPurposeCode) {
            return $value->value;
        }

        return CustomerWalletTransactionPurposeCode::tryFrom((string) $value)?->value ?? (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        return CustomerWalletTransactionPurposeCode::from((string) $value);
    }
}
