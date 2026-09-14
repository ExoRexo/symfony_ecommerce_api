<?php

namespace App\Enum;

enum CustomerWalletTransactionPurposeCode: string
{
    case WITHDRAWAL = 'WITHDRAWAL';
    case TOP_UP = 'TOP_UP';
}
