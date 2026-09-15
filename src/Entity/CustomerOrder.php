<?php

namespace App\Entity;

use App\Repository\CustomerOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerOrderRepository::class)]
#[ORM\Table(name: 'customer_orders')]
class CustomerOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Customer::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'customer_id', referencedColumnName: 'id', nullable: false)]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(targetEntity: CustomerOrderStatusType::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'status_type_id', referencedColumnName: 'id', nullable: false)]
    private ?CustomerOrderStatusType $statusType = null;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderItem::class, fetch: 'LAZY')]
    private Collection $items;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderCustomerWalletTransaction::class, fetch: 'LAZY')]
    private Collection $walletTransactions;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->walletTransactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getStatusType(): ?CustomerOrderStatusType
    {
        return $this->statusType;
    }

    public function setStatusType(CustomerOrderStatusType $statusType): self
    {
        $this->statusType = $statusType;

        return $this;
    }

    /** @return Collection<int, OrderItem> */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /** @return Collection<int, OrderCustomerWalletTransaction> */
    public function getWalletTransactions(): Collection
    {
        return $this->walletTransactions;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
