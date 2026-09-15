<?php

namespace App\Cache\Customer\Wallet;

use App\Entity\CustomerWalletTransactionPurposeType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;

class CustomerWalletCacheService
{
    private const CUSTOMER_WALLET_TRANSACTION_TYPE_CACHE_KEY = 'customer.wallet.CustomerWalletTransactionPurposeType';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CacheItemPoolInterface $cachePool,
    ) {
    }

    /**
     * @return array<string, CustomerWalletTransactionPurposeType>
     */
    public function getCustomerWalletTransactionPurposeTypes(): array
    {
        $item = $this->cachePool->getItem(self::CUSTOMER_WALLET_TRANSACTION_TYPE_CACHE_KEY);

        if (!$item->isHit()) {
            $purposes = [];

            foreach ($this->entityManager->getRepository(CustomerWalletTransactionPurposeType::class)->findAll() as $purpose) {
                $purposes[$purpose->getCode()->value] = $purpose;
            }

            $item->set($purposes);
            $this->cachePool->save($item);
        }

        return $item->get();
    }
}
