<?php

namespace App\Repository;

use App\Entity\CustomerOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

class CustomerOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerOrder::class);
    }

    public function findByIdForCancelForUpdate(int $id): ?CustomerOrder
    {
        return $this->createQueryBuilder('order')
            ->leftJoin('order.statusType', 'status')->addSelect('status')
            ->leftJoin('order.walletTransactions', 'walletTransaction')->addSelect('walletTransaction')
            ->leftJoin('walletTransaction.customerWalletTransaction', 'customerWalletTransaction')->addSelect('customerWalletTransaction')
            ->andWhere('order.id = :id')->setParameter('id', $id)
            ->getQuery()->setLockMode(LockMode::PESSIMISTIC_WRITE)->getOneOrNullResult();
    }

    public function findByIdForCompleteForUpdate(int $id): ?CustomerOrder
    {
        return $this->findByIdWithStatusForUpdate($id);
    }

    public function findByIdForDetails(int $id): ?CustomerOrder
    {
        return $this->createQueryBuilder('order')
            ->leftJoin('order.statusType', 'status')->addSelect('status')
            ->leftJoin('order.items', 'item')->addSelect('item')
            ->leftJoin('item.product', 'product')->addSelect('product')
            ->leftJoin('item.warehouseReservations', 'reservation')->addSelect('reservation')
            ->leftJoin('reservation.statusType', 'reservationStatus')->addSelect('reservationStatus')
            ->andWhere('order.id = :id')->setParameter('id', $id)
            ->getQuery()->getOneOrNullResult();
    }

    private function findByIdWithStatusForUpdate(int $id): ?CustomerOrder
    {
        return $this->createQueryBuilder('order')
            ->leftJoin('order.statusType', 'status')->addSelect('status')
            ->andWhere('order.id = :id')->setParameter('id', $id)
            ->getQuery()->setLockMode(LockMode::PESSIMISTIC_WRITE)->getOneOrNullResult();
    }
}
