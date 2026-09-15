<?php

namespace App\Repository;

use App\Entity\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }

    public function existsByAddress(string $address): bool
    {
        return $this->existsByAddressAndIdNot($address, null);
    }

    public function existsByAddressAndIdNot(string $address, ?int $id): bool
    {
        $queryBuilder = $this->createQueryBuilder('address')
            ->select('COUNT(address.id)')
            ->andWhere('address.address = :address')
            ->setParameter('address', $address);

        if ($id !== null) {
            $queryBuilder->andWhere('address.id != :id')->setParameter('id', $id);
        }

        return $queryBuilder->getQuery()->getSingleScalarResult() > 0;
    }
}
