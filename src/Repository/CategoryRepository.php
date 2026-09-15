<?php

namespace App\Repository;

use App\Entity\Catalog\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return array<int, string>
     */
    public function findTreeNames(?array $categoryIds = null): array
    {
        $connection = $this->getEntityManager()->getConnection();
        $parameters = [];
        $sql = <<<'SQL'
WITH RECURSIVE category_tree AS (
    SELECT c.id, c.name, c.parent_id, c.name::text AS tree_name, c.id AS requested_category_id
    FROM categories c
SQL;

        if ($categoryIds !== null) {
            if ($categoryIds === []) {
                return [];
            }

            $sql .= ' WHERE c.id = ANY(CAST(:category_ids AS bigint[]))';
            $parameters['category_ids'] = '{' . implode(',', $categoryIds) . '}';
        }

        $sql .= <<<'SQL'

    UNION ALL

    SELECT p.id, p.name, p.parent_id, p.name || ' > ' || ct.tree_name, ct.requested_category_id
    FROM categories p
    JOIN category_tree ct ON ct.parent_id = p.id
)
SELECT requested_category_id AS category_id, tree_name
FROM category_tree
WHERE parent_id IS NULL
SQL;

        $rows = $connection->executeQuery($sql, $parameters)->fetchAllAssociative();
        $trees = [];

        foreach ($rows as $row) {
            $trees[(int) $row['category_id']] = $row['tree_name'];
        }

        return $trees;
    }

    public function findTreeName(int $categoryId): ?string
    {
        return $this->findTreeNames([$categoryId])[$categoryId] ?? null;
    }

    public function findByNameAndIdIsNotAndParentIdIs(string $name, int $id, ?int $parentId): ?Category
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->andWhere('c.name = :name')
            ->andWhere('c.id != :id')
            ->andWhere($parentId === null ? 'c.parent IS NULL' : 'IDENTITY(c.parent) = :parentId')
            ->setParameter('name', $name)
            ->setParameter('id', $id);

        if ($parentId !== null) {
            $queryBuilder->setParameter('parentId', $parentId);
        }

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function findByNameAndParentId(string $name, ?int $parentId): ?Category
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->leftJoin('c.parent', 'parent')->addSelect('parent')
            ->andWhere('c.name = :name')
            ->andWhere($parentId === null ? 'c.parent IS NULL' : 'IDENTITY(c.parent) = :parentId')
            ->setParameter('name', $name);

        if ($parentId !== null) {
            $queryBuilder->setParameter('parentId', $parentId);
        }

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /** @return list<Category> */
    public function findAllWithParent(): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.parent', 'parent')->addSelect('parent')
            ->getQuery()
            ->getResult();
    }

    public function findByIdForUpdate(int $id): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->setLockMode(LockMode::PESSIMISTIC_WRITE)
            ->getOneOrNullResult();
    }
}
