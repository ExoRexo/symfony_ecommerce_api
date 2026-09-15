<?php

namespace App\Repository;

use App\Entity\OrderItemWarehouseReservation;
use App\Enum\OrderItemReservationStatusCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

class OrderItemWarehouseReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderItemWarehouseReservation::class);
    }

    public function existsByOrderItemIdAndWarehouseIdAndStatusCode(
        int $orderItemId,
        int $warehouseId,
        OrderItemReservationStatusCode $statusCode,
    ): bool {
        return $this->createQueryBuilder('reservation')
            ->select('COUNT(reservation.id)')
            ->innerJoin('reservation.statusType', 'status')
            ->andWhere('IDENTITY(reservation.orderItem) = :orderItemId')
            ->andWhere('IDENTITY(reservation.warehouse) = :warehouseId')
            ->andWhere('status.code = :statusCode')
            ->setParameter('orderItemId', $orderItemId)
            ->setParameter('warehouseId', $warehouseId)
            ->setParameter('statusCode', $statusCode)
            ->getQuery()->getSingleScalarResult() > 0;
    }

    public function findByIdForItemReservationResponse(int $id): ?OrderItemWarehouseReservation
    {
        return $this->createQueryBuilder('reservation')
            ->leftJoin('reservation.orderItem', 'orderItem')->addSelect('orderItem')
            ->leftJoin('reservation.warehouse', 'warehouse')->addSelect('warehouse')
            ->leftJoin('warehouse.address', 'address')->addSelect('address')
            ->leftJoin('reservation.statusType', 'status')->addSelect('status')
            ->andWhere('reservation.id = :id')->setParameter('id', $id)
            ->getQuery()->getOneOrNullResult();
    }

    public function findByIdForCancelForUpdate(int $id): ?OrderItemWarehouseReservation
    {
        return $this->createQueryBuilder('reservation')
            ->leftJoin('reservation.statusType', 'status')->addSelect('status')
            ->leftJoin('reservation.orderItem', 'orderItem')->addSelect('orderItem')
            ->andWhere('reservation.id = :id')->setParameter('id', $id)
            ->getQuery()->setLockMode(LockMode::PESSIMISTIC_WRITE)->getOneOrNullResult();
    }
}
