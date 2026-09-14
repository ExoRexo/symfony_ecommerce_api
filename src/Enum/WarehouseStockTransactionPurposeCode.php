<?php

namespace App\Enum;

enum WarehouseStockTransactionPurposeCode: string
{
    case SALE = 'SALE';
    case PURCHASE = 'PURCHASE';
    case INVENTORY_ADJUSTMENT = 'INVENTORY_ADJUSTMENT';

    /**
     * @return array<int, WarehouseStockTransactionOperationCode>
     */
    public function allowedOperations(): array
    {
        return match ($this) {
            self::SALE => [WarehouseStockTransactionOperationCode::DECREASE],
            self::PURCHASE => [WarehouseStockTransactionOperationCode::INCREASE],
            self::INVENTORY_ADJUSTMENT => [
                WarehouseStockTransactionOperationCode::INCREASE,
                WarehouseStockTransactionOperationCode::DECREASE,
            ],
        };
    }
}


