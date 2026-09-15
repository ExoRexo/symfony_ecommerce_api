<?php

namespace App\Entity\Customer\Wallet;

use App\Entity\Customer\Customer;
use App\Repository\CustomerWalletRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerWalletRepository::class)]
#[ORM\Table(name: 'customer_wallets')]
class CustomerWallet
{
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: Customer::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'customer_id', referencedColumnName: 'id', nullable: false)]
    private ?Customer $customer = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $balance = null;

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getBalance(): ?string
    {
        return $this->balance;
    }

    public function setBalance(string $balance): self
    {
        $this->balance = $balance;

        return $this;
    }
}
