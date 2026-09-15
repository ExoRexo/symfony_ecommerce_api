<?php

namespace App\Cache\Inventory\Warehouse;

use App\Entity\WarehouseStockTransactionPurposeType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;

class WarehouseCacheService
{
    private const WAREHOUSE_STOCK_TRANSACTION_PURPOSE_TYPE_CACHE_KEY = 'inventory.warehouse.warehouseStockTransactionPurposeType';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CacheItemPoolInterface $cachePool,
    ) {
    }

    /**
     * @return array<string, WarehouseStockTransactionPurposeType>
     */
    public function getWarehouseStockTransactionPurposeTypes(): array
    {
        $item = $this->cachePool->getItem(self::WAREHOUSE_STOCK_TRANSACTION_PURPOSE_TYPE_CACHE_KEY);

        if (!$item->isHit()) {
            $purposes = [];

            foreach ($this->entityManager->getRepository(WarehouseStockTransactionPurposeType::class)->findAll() as $purpose) {
                $purposes[$purpose->getCode()->value] = $purpose;
            }

            $item->set($purposes);
            $this->cachePool->save($item);
        }

        return $item->get();
    }
}
