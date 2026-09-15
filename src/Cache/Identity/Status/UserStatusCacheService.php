<?php

namespace App\Cache\Identity\Status;

use App\Entity\UserStatusType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;

class UserStatusCacheService
{
    private const CACHE_KEY = 'user_status_type';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CacheItemPoolInterface $cachePool,
    ) {
    }

    /**
     * @return array<string, UserStatusType>
     */
    public function getStatusTypes(): array
    {
        $item = $this->cachePool->getItem(self::CACHE_KEY);

        if (!$item->isHit()) {
            $statuses = [];

            foreach ($this->entityManager->getRepository(UserStatusType::class)->findAll() as $status) {
                $statuses[$status->getCode()->value] = $status;
            }

            $item->set($statuses);
            $this->cachePool->save($item);
        }

        return $item->get();
    }
}
