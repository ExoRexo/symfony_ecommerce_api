<?php

namespace App\Entity\Customer\Order;

use App\Enum\CustomerOrderStatusCode;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'customer_order_status_types')]
class CustomerOrderStatusType
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'smallint')]
    private ?int $id = null;

    #[ORM\Column(type: 'customer_order_status_code', length: 50, unique: true, nullable: false)]
    private CustomerOrderStatusCode $code;

    #[ORM\Column(type: 'string', length: 120)]
    private ?string $label = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): CustomerOrderStatusCode
    {
        return $this->code;
    }

    public function setCode(CustomerOrderStatusCode $code): self
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
