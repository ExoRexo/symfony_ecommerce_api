<?php

namespace App\Enum;

enum OrderItemReservationStatusCode: string
{
    case ACTIVE = 'ACTIVE';
    case CANCELLED = 'CANCELLED';
    case FINISHED = 'FINISHED';
}
