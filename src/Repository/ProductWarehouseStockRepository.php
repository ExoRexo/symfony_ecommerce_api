<?php

namespace App\Repository;

use App\Entity\Inventory\ProductWarehouseStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

class ProductWarehouseStockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductWarehouseStock::class);
    }

    public function findByProductIdAndWarehouseIdForUpdate(int $productId, int $warehouseId): ?ProductWarehouseStock
    {
        return $this->createQueryBuilder('stock')
            ->andWhere('IDENTITY(stock.product) = :productId')
            ->andWhere('IDENTITY(stock.warehouse) = :warehouseId')
            ->setParameter('productId', $productId)
            ->setParameter('warehouseId', $warehouseId)
            ->getQuery()
            ->setLockMode(LockMode::PESSIMISTIC_WRITE)
            ->getOneOrNullResult();
    }
}
