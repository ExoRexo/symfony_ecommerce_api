<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_items')]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantity = null;

    #[ORM\ManyToOne(targetEntity: Product::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(name: 'unit_price_rub', type: 'decimal', precision: 10, scale: 2)]
    private ?string $unitPriceRub = null;

    #[ORM\Column(name: 'price_total_rub', type: 'decimal', precision: 10, scale: 2)]
    private ?string $priceTotalRub = null;

    #[ORM\ManyToOne(targetEntity: CustomerOrder::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id', nullable: false)]
    private ?CustomerOrder $order = null;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getUnitPriceRub(): ?string
    {
        return $this->unitPriceRub;
    }

    public function setUnitPriceRub(string $unitPriceRub): self
    {
        $this->unitPriceRub = $unitPriceRub;

        return $this;
    }

    public function getPriceTotalRub(): ?string
    {
        return $this->priceTotalRub;
    }

    public function setPriceTotalRub(string $priceTotalRub): self
    {
        $this->priceTotalRub = $priceTotalRub;

        return $this;
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
