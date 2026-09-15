<?php

namespace App\Entity\Identity;

use App\Enum\PermissionCode;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'permissions')]
class Permission
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(type: 'permission_code', length: 255, unique: true, nullable: false)]
    private PermissionCode $code;

    #[ORM\Column(type: 'string', length: 120)]
    private ?string $label = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'permissions')]
    #[ORM\JoinTable(
        name: 'role_permissions',
        joinColumns: [new ORM\JoinColumn(name: 'permission_id', referencedColumnName: 'id')],
        inverseJoinColumns: [new ORM\JoinColumn(name: 'role_id', referencedColumnName: 'id')]
    )]
    private Collection $roles;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'directPermissions')]
    private Collection $users;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): PermissionCode
    {
        return $this->code;
    }

    public function setCode(PermissionCode $code): self
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
