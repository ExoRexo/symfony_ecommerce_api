<?php

namespace App\Repository;

use App\Entity\OrderItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

class OrderItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderItem::class);
    }

    /** @return list<OrderItem> */
    public function findByOrderIdForCancelForUpdate(int $orderId): array
    {
        return $this->findByOrderIdWithReservationsForUpdate($orderId);
    }

    /** @return list<OrderItem> */
    public function findByOrderIdForCompleteForUpdate(int $orderId): array
    {
        return $this->findByOrderIdWithReservationsForUpdate($orderId);
    }

    public function findTotalAmountByOrderId(int $orderId): ?string
    {
        $amount = $this->createQueryBuilder('item')
            ->select('SUM(item.priceTotalRub)')
            ->andWhere('IDENTITY(item.order) = :orderId')
            ->setParameter('orderId', $orderId)
            ->getQuery()
            ->getSingleScalarResult();

        return $amount === null ? null : (string) $amount;
    }

    public function findByIdForUpdate(int $id): ?OrderItem
    {
        return $this->createQueryBuilder('item')
            ->andWhere('item.id = :id')->setParameter('id', $id)
            ->getQuery()->setLockMode(LockMode::PESSIMISTIC_WRITE)->getOneOrNullResult();
    }

    /** @return list<OrderItem> */
    private function findByOrderIdWithReservationsForUpdate(int $orderId): array
    {
        return $this->createQueryBuilder('item')
            ->leftJoin('item.warehouseReservations', 'reservation')->addSelect('reservation')
            ->andWhere('IDENTITY(item.order) = :orderId')->setParameter('orderId', $orderId)
            ->orderBy('item.id', 'ASC')
            ->getQuery()->setLockMode(LockMode::PESSIMISTIC_WRITE)->getResult();
    }
}
