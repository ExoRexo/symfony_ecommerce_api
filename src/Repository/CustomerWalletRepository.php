<?php

namespace App\Repository;

use App\Entity\CustomerWallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

class CustomerWalletRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerWallet::class);
    }

    public function findByCustomerIdForUpdate(int $customerId): ?CustomerWallet
    {
        return $this->createQueryBuilder('wallet')
            ->andWhere('IDENTITY(wallet.customer) = :customerId')
            ->setParameter('customerId', $customerId)
            ->getQuery()
            ->setLockMode(LockMode::PESSIMISTIC_WRITE)
            ->getOneOrNullResult();
    }
}
