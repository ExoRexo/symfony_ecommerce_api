<?php

namespace App\Repository;

use App\Entity\Warehouse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

class WarehouseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Warehouse::class);
    }

    public function existsByName(string $name): bool
    {
        return $this->createQueryBuilder('warehouse')->select('COUNT(warehouse.id)')
            ->andWhere('warehouse.name = :name')->setParameter('name', $name)
            ->getQuery()->getSingleScalarResult() > 0;
    }

    public function existsByNameAndIdNot(string $name, int $id): bool
    {
        return $this->createQueryBuilder('warehouse')->select('COUNT(warehouse.id)')
            ->andWhere('warehouse.name = :name AND warehouse.id != :id')
            ->setParameter('name', $name)
            ->setParameter('id', $id)
            ->getQuery()->getSingleScalarResult() > 0;
    }

    public function findByIdForUpdate(int $id): ?Warehouse
    {
        return $this->createQueryBuilder('warehouse')
            ->leftJoin('warehouse.address', 'address')->addSelect('address')
            ->andWhere('warehouse.id = :id')->setParameter('id', $id)
            ->getQuery()->setLockMode(LockMode::PESSIMISTIC_WRITE)->getOneOrNullResult();
    }

    /** @return list<int> */
    public function findAllIds(): array
    {
        return array_map('intval', $this->createQueryBuilder('warehouse')->select('warehouse.id')->getQuery()->getSingleColumnResult());
    }
}
