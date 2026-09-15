<?php

namespace App\Security\Principal;

use App\Enum\PermissionCode;
use App\Enum\RoleCode;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class UserPrincipal implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @param list<RoleCode> $roleCodes
     * @param list<PermissionCode> $permissionCodes
     */
    public function __construct(
        private int $id,
        private string $email,
        private string $passwordHash,
        private bool $enabled,
        private array $roleCodes,
        private array $permissionCodes,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    /** @return list<RoleCode> */
    public function getRoleCodes(): array
    {
        return $this->roleCodes;
    }

    /** @return list<PermissionCode> */
    public function getPermissionCodes(): array
    {
        return $this->permissionCodes;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->passwordHash;
    }

    /** @return list<string> */
    public function getRoles(): array
    {
        $authorities = [];

        foreach ($this->roleCodes as $roleCode) {
            $authorities[] = 'ROLE_' . $roleCode->value;
        }

        foreach ($this->permissionCodes as $permissionCode) {
            $authorities[] = 'PERMISSION_' . $permissionCode->value;
        }

        return array_values(array_unique($authorities));
    }

    public function eraseCredentials(): void
    {
    }
}
