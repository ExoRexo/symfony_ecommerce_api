<?php

namespace App\Entity;

use App\Repository\CustomerWalletTransactionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerWalletTransactionRepository::class)]
#[ORM\Table(name: 'customer_wallet_transactions')]
class CustomerWalletTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CustomerWallet::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'wallet_id', referencedColumnName: 'customer_id', nullable: false)]
    private ?CustomerWallet $wallet = null;

    #[ORM\Column(name: 'old_balance', type: 'decimal', precision: 10, scale: 2)]
    private ?string $oldBalance = null;

    #[ORM\Column(name: 'new_balance', type: 'decimal', precision: 10, scale: 2)]
    private ?string $newBalance = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $delta = null;

    #[ORM\ManyToOne(targetEntity: CustomerWalletTransactionPurposeType::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'purpose_type_id', referencedColumnName: 'id', nullable: false)]
    private ?CustomerWalletTransactionPurposeType $purposeType = null;

    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: CustomerOrder::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id', nullable: true)]
    private ?CustomerOrder $order = null;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWallet(): ?CustomerWallet
    {
        return $this->wallet;
    }

    public function setWallet(CustomerWallet $wallet): self
    {
        $this->wallet = $wallet;

        return $this;
    }

    public function getOldBalance(): ?string
    {
        return $this->oldBalance;
    }

    public function setOldBalance(string $oldBalance): self
    {
        $this->oldBalance = $oldBalance;

        return $this;
    }

    public function getNewBalance(): ?string
    {
        return $this->newBalance;
    }

    public function setNewBalance(string $newBalance): self
    {
        $this->newBalance = $newBalance;

        return $this;
    }

    public function getDelta(): ?string
    {
        return $this->delta;
    }

    public function setDelta(string $delta): self
    {
        $this->delta = $delta;

        return $this;
    }

    public function getPurposeType(): ?CustomerWalletTransactionPurposeType
    {
        return $this->purposeType;
    }

    public function setPurposeType(CustomerWalletTransactionPurposeType $purposeType): self
    {
        $this->purposeType = $purposeType;

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
