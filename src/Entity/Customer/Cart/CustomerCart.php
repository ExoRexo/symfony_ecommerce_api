<?php

namespace App\Entity\Customer\Cart;

use App\Entity\Customer\Customer;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'customer_carts')]
class CustomerCart
{
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: Customer::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'customer_id', referencedColumnName: 'id', nullable: false)]
    private ?Customer $customer = null;

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
