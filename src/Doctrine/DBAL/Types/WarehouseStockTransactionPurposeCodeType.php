<?php

namespace App\Doctrine\DBAL\Types;

use App\Enum\WarehouseStockTransactionPurposeCode;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class WarehouseStockTransactionPurposeCodeType extends StringType
{
    public const NAME = 'warehouse_stock_transaction_purpose_code';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof WarehouseStockTransactionPurposeCode) {
            return $value->value;
        }

        return WarehouseStockTransactionPurposeCode::tryFrom((string) $value)?->value ?? (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        return WarehouseStockTransactionPurposeCode::from((string) $value);
    }
}
