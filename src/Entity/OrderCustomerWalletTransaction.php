<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_custm_wallt_transactions')]
class OrderCustomerWalletTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CustomerOrder::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id', nullable: false)]
    private ?CustomerOrder $order = null;

    #[ORM\ManyToOne(targetEntity: CustomerWalletTransaction::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'custm_wallet_tran_id', referencedColumnName: 'id', nullable: false)]
    private ?CustomerWalletTransaction $customerWalletTransaction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?CustomerOrder
    {
        return $this->order;
    }

    public function setOrder(CustomerOrder $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getCustomerWalletTransaction(): ?CustomerWalletTransaction
    {
        return $this->customerWalletTransaction;
    }

    public function setCustomerWalletTransaction(CustomerWalletTransaction $customerWalletTransaction): self
    {
        $this->customerWalletTransaction = $customerWalletTransaction;

        return $this;
    }
}
