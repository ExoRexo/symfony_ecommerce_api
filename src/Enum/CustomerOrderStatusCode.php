<?php

namespace App\Enum;

enum CustomerOrderStatusCode: string
{
    case CREATED = 'CREATED';
    case PENDING_PAYMENT = 'PENDING_PAYMENT';
    case PAID = 'PAID';
    case COMPLETED = 'COMPLETED';
    case CANCELLED = 'CANCELLED';

    public function isFinal(): bool
    {
        return match ($this) {
            self::COMPLETED,
            self::CANCELLED => true,
            default => false,
        };
    }
}
