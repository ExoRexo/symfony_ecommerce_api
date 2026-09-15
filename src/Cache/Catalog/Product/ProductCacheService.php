<?php

namespace App\Cache\Catalog\Product;

use App\Entity\Catalog\ProductStatusType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;

class ProductCacheService
{
    private const PRODUCT_STATUS_CACHE_KEY = 'catalog.category.productStatus';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CacheItemPoolInterface $cachePool,
    ) {
    }

    /**
     * @return array<string, ProductStatusType>
     */
    public function getProductStatuses(): array
    {
        $item = $this->cachePool->getItem(self::PRODUCT_STATUS_CACHE_KEY);

        if (!$item->isHit()) {
            $statuses = [];

            foreach ($this->entityManager->getRepository(ProductStatusType::class)->findAll() as $status) {
                $statuses[$status->getCode()->value] = $status;
            }

            $item->set($statuses);
            $this->cachePool->save($item);
        }

        return $item->get();
    }
}
