<?php

namespace App\Cache\Identity\Authority;

use App\Entity\Identity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;

class RoleCacheService
{
    private const CACHE_KEY = 'user_roles';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CacheItemPoolInterface $cachePool,
    ) {
    }

    /**
     * @return array<string, Role>
     */
    public function getRoles(): array
    {
        $item = $this->cachePool->getItem(self::CACHE_KEY);

        if (!$item->isHit()) {
            $roles = [];

            foreach ($this->entityManager->getRepository(Role::class)->findAll() as $role) {
                $roles[$role->getCode()->value] = $role;
            }

            $item->set($roles);
            $this->cachePool->save($item);
        }

        return $item->get();
    }
}
