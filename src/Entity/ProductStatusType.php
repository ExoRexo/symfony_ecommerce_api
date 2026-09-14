<?php

namespace App\Entity;

use App\Enum\ProductStatusCode;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'product_status_types')]
class ProductStatusType
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'smallint')]
    private ?int $id = null;

    #[ORM\Column(type: 'product_status_code', length: 100, unique: true, nullable: false)]
    private ProductStatusCode $code;

    #[ORM\Column(type: 'string', length: 120)]
    private ?string $label = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ProductStatusCode
    {
        return $this->code;
    }

    public function setCode(ProductStatusCode $code): self
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
