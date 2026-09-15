<?php

namespace App\Entity\Customer;

use App\Entity\Identity\User;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'customers')]
class Customer extends User
{
}
