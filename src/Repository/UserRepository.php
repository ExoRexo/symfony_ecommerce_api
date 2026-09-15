<?php

namespace App\Repository;

use App\Entity\Identity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function existsByEmail(string $email): bool
    {
        return $this->createQueryBuilder('user')->select('COUNT(user.id)')
            ->andWhere('user.email = :email')->setParameter('email', $email)
            ->getQuery()->getSingleScalarResult() > 0;
    }

    public function findByIdForUserDetails(int $id): ?User
    {
        return $this->findUserDetails('user.id = :value', $id);
    }

    public function findByEmailForUserDetails(string $email): ?User
    {
        return $this->findUserDetails('user.email = :value', $email);
    }

    public function findByIdForUserProfile(int $id): ?User
    {
        return $this->createQueryBuilder('user')
            ->leftJoin('user.statusType', 'status')->addSelect('status')
            ->andWhere('user.id = :id')->setParameter('id', $id)
            ->getQuery()->getOneOrNullResult();
    }

    private function findUserDetails(string $condition, int|string $value): ?User
    {
        return $this->createQueryBuilder('user')
            ->leftJoin('user.directPermissions', 'permission')->addSelect('permission')
            ->leftJoin('user.roles', 'role')->addSelect('role')
            ->leftJoin('role.permissions', 'rolePermission')->addSelect('rolePermission')
            ->leftJoin('user.statusType', 'status')->addSelect('status')
            ->andWhere($condition)->setParameter('value', $value)
            ->getQuery()->getOneOrNullResult();
    }
}
