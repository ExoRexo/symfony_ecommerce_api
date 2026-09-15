<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function existsByNameAndCategoryId(string $name, int $categoryId): bool
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->andWhere('p.name = :name AND IDENTITY(p.category) = :categoryId')
            ->setParameter('name', $name)
            ->setParameter('categoryId', $categoryId)
            ->getQuery()->getSingleScalarResult() > 0;
    }

    public function existsByIdNotAndNameAndCategoryId(int $id, string $name, int $categoryId): bool
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->andWhere('p.id != :id AND p.name = :name AND IDENTITY(p.category) = :categoryId')
            ->setParameter('id', $id)
            ->setParameter('name', $name)
            ->setParameter('categoryId', $categoryId)
            ->getQuery()->getSingleScalarResult() > 0;
    }

    public function findByIdForUpdate(int $id): ?Product
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.statusType', 'status')->addSelect('status')
            ->leftJoin('p.category', 'category')->addSelect('category')
            ->leftJoin('category.parent', 'parent')->addSelect('parent')
            ->andWhere('p.id = :id')->setParameter('id', $id)
            ->getQuery()->setLockMode(LockMode::PESSIMISTIC_WRITE)->getOneOrNullResult();
    }

    /** @return list<int> */
    public function findAllIds(): array
    {
        return array_map('intval', $this->createQueryBuilder('p')->select('p.id')->getQuery()->getSingleColumnResult());
    }
}
