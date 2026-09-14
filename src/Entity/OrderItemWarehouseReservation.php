<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_item_warehouse_reservations')]
class OrderItemWarehouseReservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: OrderItem::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'order_item_id', referencedColumnName: 'id', nullable: false)]
    private ?OrderItem $orderItem = null;

    #[ORM\ManyToOne(targetEntity: Warehouse::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'warehouse_id', referencedColumnName: 'id', nullable: false)]
    private ?Warehouse $warehouse = null;

    #[ORM\Column(name: 'reserved_quantity', type: 'integer')]
    private ?int $reservedQuantity = null;

    #[ORM\ManyToOne(targetEntity: OrderItemReservationStatusType::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'status_type_id', referencedColumnName: 'id', nullable: false)]
    private ?OrderItemReservationStatusType $statusType = null;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderItem(): ?OrderItem
    {
        return $this->orderItem;
    }

    public function setOrderItem(OrderItem $orderItem): self
    {
        $this->orderItem = $orderItem;

        return $this;
    }

    public function getWarehouse(): ?Warehouse
    {
        return $this->warehouse;
    }

    public function setWarehouse(Warehouse $warehouse): self
    {
        $this->warehouse = $warehouse;

        return $this;
    }

    public function getReservedQuantity(): ?int
    {
        return $this->reservedQuantity;
    }

    public function setReservedQuantity(int $reservedQuantity): self
    {
        $this->reservedQuantity = $reservedQuantity;

        return $this;
    }

    public function getStatusType(): ?OrderItemReservationStatusType
    {
        return $this->statusType;
    }

    public function setStatusType(OrderItemReservationStatusType $statusType): self
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
