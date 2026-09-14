<?php

namespace App\Enum;

enum RoleCode: string
{
    case ADMIN = 'ADMIN';
    case MANAGER = 'MANAGER';
    case CUSTOMER = 'CUSTOMER';
}
