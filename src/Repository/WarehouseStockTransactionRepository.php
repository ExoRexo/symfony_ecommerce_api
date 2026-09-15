<?php

namespace App\Repository;

use App\Entity\Inventory\WarehouseStockTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WarehouseStockTransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarehouseStockTransaction::class);
    }

    public function findByIdForStockManagementResponse(int $id): ?WarehouseStockTransaction
    {
        return $this->createQueryBuilder('transaction')
            ->leftJoin('transaction.product', 'product')->addSelect('product')
            ->leftJoin('transaction.warehouse', 'warehouse')->addSelect('warehouse')
            ->leftJoin('warehouse.address', 'address')->addSelect('address')
            ->leftJoin('transaction.purposeType', 'purpose')->addSelect('purpose')
            ->leftJoin('transaction.user', 'user')->addSelect('user')
            ->andWhere('transaction.id = :id')->setParameter('id', $id)
            ->getQuery()->getOneOrNullResult();
    }
}
