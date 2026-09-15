<?php

namespace App\Cache\Identity\Authority;

use App\Entity\Permission;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;

class PermissionCacheService
{
    private const CACHE_KEY = 'user_permissions';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CacheItemPoolInterface $cachePool,
    ) {
    }

    /**
     * @return array<string, Permission>
     */
    public function getPermissions(): array
    {
        $item = $this->cachePool->getItem(self::CACHE_KEY);

        if (!$item->isHit()) {
            $permissions = [];

            foreach ($this->entityManager->getRepository(Permission::class)->findAll() as $permission) {
                $permissions[$permission->getCode()->value] = $permission;
            }

            $item->set($permissions);
            $this->cachePool->save($item);
        }

        return $item->get();
    }
}
