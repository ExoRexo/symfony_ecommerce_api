<?php

namespace App\Enum;

enum WarehouseStockTransactionOperationCode: string
{
    case INCREASE = 'INCREASE';
    case DECREASE = 'DECREASE';
}