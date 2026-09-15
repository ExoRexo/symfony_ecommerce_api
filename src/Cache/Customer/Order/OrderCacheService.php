<?php

namespace App\Cache\Customer\Order;

use App\Entity\CustomerOrderStatusType;
use App\Entity\OrderItemReservationStatusType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;

class OrderCacheService
{
    private const CUSTOMER_ORDER_STATUS_CACHE_KEY = 'customer.order.CustomerOrderStatusCode';
    private const ORDER_ITEM_RESERVATION_STATUS_CACHE_KEY = 'customer.order.OrderItemReservationStatusType';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CacheItemPoolInterface $cachePool,
    ) {
    }

    /**
     * @return array<string, CustomerOrderStatusType>
     */
    public function getCustomerOrderStatusTypes(): array
    {
        $item = $this->cachePool->getItem(self::CUSTOMER_ORDER_STATUS_CACHE_KEY);

        if (!$item->isHit()) {
            $statuses = [];

            foreach ($this->entityManager->getRepository(CustomerOrderStatusType::class)->findAll() as $status) {
                $statuses[$status->getCode()->value] = $status;
            }

            $item->set($statuses);
            $this->cachePool->save($item);
        }

        return $item->get();
    }

    /**
     * @return array<string, OrderItemReservationStatusType>
     */
    public function getOrderItemReservationStatusTypes(): array
    {
        $item = $this->cachePool->getItem(self::ORDER_ITEM_RESERVATION_STATUS_CACHE_KEY);

        if (!$item->isHit()) {
            $statuses = [];

            foreach ($this->entityManager->getRepository(OrderItemReservationStatusType::class)->findAll() as $status) {
                $statuses[$status->getCode()->value] = $status;
            }

            $item->set($statuses);
            $this->cachePool->save($item);
        }

        return $item->get();
    }
}
