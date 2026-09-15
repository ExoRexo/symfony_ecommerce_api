<?php

namespace App\Entity\Customer\Cart;

use App\Entity\Catalog\Product;
use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
#[ORM\Table(name: 'cart_items')]
#[ORM\UniqueConstraint(name: 'uq_cart_product', columns: ['cart_id', 'product_id'])]
class CartItem
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

    #[ORM\ManyToOne(targetEntity: CustomerCart::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'cart_id', referencedColumnName: 'customer_id', nullable: false)]
    private ?CustomerCart $cart = null;

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

    public function getCart(): ?CustomerCart
    {
        return $this->cart;
    }

    public function setCart(CustomerCart $cart): self
    {
        $this->cart = $cart;

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
