<?php

namespace App\Repository;

use App\Entity\CustomerWalletTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CustomerWalletTransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerWalletTransaction::class);
    }

    public function findByIdForWalletUpdateResponse(int $id): ?CustomerWalletTransaction
    {
        return $this->createQueryBuilder('transaction')
            ->leftJoin('transaction.purposeType', 'purpose')->addSelect('purpose')
            ->leftJoin('transaction.user', 'user')->addSelect('user')
            ->andWhere('transaction.id = :id')->setParameter('id', $id)
            ->getQuery()->getOneOrNullResult();
    }
}
