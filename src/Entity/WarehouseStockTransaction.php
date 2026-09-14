<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'warehouse_stock_transactions')]
class WarehouseStockTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(targetEntity: Warehouse::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'warehouse_id', referencedColumnName: 'id', nullable: false)]
    private ?Warehouse $warehouse = null;

    #[ORM\Column(name: 'old_quantity', type: 'integer')]
    private ?int $oldQuantity = null;

    #[ORM\Column(name: 'new_quantity', type: 'integer')]
    private ?int $newQuantity = null;

    #[ORM\Column(type: 'integer')]
    private ?int $delta = null;

    #[ORM\ManyToOne(targetEntity: WarehouseStockTransactionPurposeType::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'purpose_type_id', referencedColumnName: 'id', nullable: false)]
    private ?WarehouseStockTransactionPurposeType $purposeType = null;

    #[ORM\ManyToOne(targetEntity: ProductWarehouseStock::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'product_warehouse_stock_id', referencedColumnName: 'id', nullable: false)]
    private ?ProductWarehouseStock $productWarehouseStock = null;

    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private ?User $user = null;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOldQuantity(): ?int
    {
        return $this->oldQuantity;
    }

    public function setOldQuantity(int $oldQuantity): self
    {
        $this->oldQuantity = $oldQuantity;

        return $this;
    }

    public function getNewQuantity(): ?int
    {
        return $this->newQuantity;
    }

    public function setNewQuantity(int $newQuantity): self
    {
        $this->newQuantity = $newQuantity;

        return $this;
    }

    public function getDelta(): ?int
    {
        return $this->delta;
    }

    public function setDelta(int $delta): self
    {
        $this->delta = $delta;

        return $this;
    }

    public function getPurposeType(): ?WarehouseStockTransactionPurposeType
    {
        return $this->purposeType;
    }

    public function setPurposeType(WarehouseStockTransactionPurposeType $purposeType): self
    {
        $this->purposeType = $purposeType;

        return $this;
    }

    public function getProductWarehouseStock(): ?ProductWarehouseStock
    {
        return $this->productWarehouseStock;
    }

    public function setProductWarehouseStock(ProductWarehouseStock $productWarehouseStock): self
    {
        $this->productWarehouseStock = $productWarehouseStock;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

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
