<?php

namespace App\Entity;

use App\Enum\CustomerWalletTransactionPurposeCode;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'c_wallt_transaction_purpose_types')]
class CustomerWalletTransactionPurposeType
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'smallint')]
    private ?int $id = null;

    #[ORM\Column(type: 'customer_wallet_transaction_purpose_code', length: 30, unique: true, nullable: false)]
    private CustomerWalletTransactionPurposeCode $code;

    #[ORM\Column(type: 'string', length: 120)]
    private ?string $label = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): CustomerWalletTransactionPurposeCode
    {
        return $this->code;
    }

    public function setCode(CustomerWalletTransactionPurposeCode $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
