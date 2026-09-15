<?php

namespace App\Repository;

use App\Entity\CartItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

class CartItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartItem::class);
    }

    public function findByCartCustomerIdAndProductIdForUpdate(int $customerId, int $productId): ?CartItem
    {
        return $this->createQueryBuilder('item')
            ->andWhere('IDENTITY(item.cart) = :customerId')
            ->andWhere('IDENTITY(item.product) = :productId')
            ->setParameter('customerId', $customerId)
            ->setParameter('productId', $productId)
            ->getQuery()->setLockMode(LockMode::PESSIMISTIC_WRITE)->getOneOrNullResult();
    }

    /** @return list<CartItem> */
    public function findAllByCartCustomerIdForUpdate(int $customerId): array
    {
        return $this->createQueryBuilder('item')
            ->leftJoin('item.product', 'product')->addSelect('product')
            ->andWhere('IDENTITY(item.cart) = :customerId')->setParameter('customerId', $customerId)
            ->orderBy('product.id', 'ASC')->addOrderBy('item.id', 'ASC')
            ->getQuery()->setLockMode(LockMode::PESSIMISTIC_WRITE)->getResult();
    }

    public function deleteByCartCustomerIdAndProductId(int $customerId, int $productId): int
    {
        return $this->createQueryBuilder('item')
            ->delete()
            ->andWhere('IDENTITY(item.cart) = :customerId')
            ->andWhere('IDENTITY(item.product) = :productId')
            ->setParameter('customerId', $customerId)
            ->setParameter('productId', $productId)
            ->getQuery()->execute();
    }
}
