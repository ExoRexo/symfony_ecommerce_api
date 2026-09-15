<?php

namespace App\Entity\Inventory;

use App\Entity\Catalog\Product;
use App\Repository\ProductWarehouseStockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductWarehouseStockRepository::class)]
#[ORM\Table(name: 'product_wh_stocks')]
#[ORM\UniqueConstraint(name: 'uq_product_wh', columns: ['product_id', 'warehouse_id'])]
class ProductWarehouseStock
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(name: 'physical_quantity', type: 'integer')]
    private ?int $physicalQuantity = null;

    #[ORM\Column(name: 'reserved_quantity', type: 'integer')]
    private ?int $reservedQuantity = null;

    #[ORM\ManyToOne(targetEntity: Product::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(targetEntity: Warehouse::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'warehouse_id', referencedColumnName: 'id', nullable: false)]
    private ?Warehouse $warehouse = null;

    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhysicalQuantity(): ?int
    {
        return $this->physicalQuantity;
    }

    public function setPhysicalQuantity(int $physicalQuantity): self
    {
        $this->physicalQuantity = $physicalQuantity;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
