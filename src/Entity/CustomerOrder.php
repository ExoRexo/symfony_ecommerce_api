<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
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

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $createdAt = null;

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
