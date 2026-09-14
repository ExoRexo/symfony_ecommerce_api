<?php

namespace App\Entity;

use App\Enum\OrderItemReservationStatusCode;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_item_reservation_status_types')]
class OrderItemReservationStatusType
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'smallint')]
    private ?int $id = null;

    #[ORM\Column(type: 'order_item_reservation_status_code', length: 50, unique: true, nullable: false)]
    private OrderItemReservationStatusCode $code;

    #[ORM\Column(type: 'string', length: 120)]
    private ?string $label = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): OrderItemReservationStatusCode
    {
        return $this->code;
    }

    public function setCode(OrderItemReservationStatusCode $code): self
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
