<?php

namespace App\Security\Principal;

use App\Entity\Identity\User;
use App\Enum\UserStatusCode;

final class UserPrincipalFactory
{
    public function fromUser(User $user): UserPrincipal
    {
        $userId = $user->getId();
        $email = $user->getEmail();
        $passwordHash = $user->getPasswordHash();

        if ($userId === null || $email === null || $passwordHash === null) {
            throw new \LogicException('Cannot build a principal from an incomplete user.');
        }

        $roleCodes = [];
        $permissionCodes = [];

        foreach ($user->getRolesCollection() as $role) {
            $roleCode = $role->getCode();
            $roleCodes[$roleCode->value] = $roleCode;

            foreach ($role->getPermissions() as $permission) {
                $permissionCode = $permission->getCode();
                $permissionCodes[$permissionCode->value] = $permissionCode;
            }
        }

        foreach ($user->getDirectPermissions() as $permission) {
            $permissionCode = $permission->getCode();
            $permissionCodes[$permissionCode->value] = $permissionCode;
        }

        return new UserPrincipal(
            $userId,
            $email,
            $passwordHash,
            $user->getStatusType()?->getCode() === UserStatusCode::ACTIVE,
            array_values($roleCodes),
            array_values($permissionCodes),
        );
    }
}
