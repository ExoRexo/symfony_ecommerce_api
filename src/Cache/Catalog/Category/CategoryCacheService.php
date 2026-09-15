<?php

namespace App\Cache\Catalog\Category;

use App\Repository\CategoryRepository;
use Psr\Cache\CacheItemPoolInterface;

class CategoryCacheService
{
    private const CACHE_KEY = 'catalog.category.categoryTree';

    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly CacheItemPoolInterface $cachePool,
    ) {
    }

    public function getCategoryTree(int $categoryId): string
    {
        $cacheKey = self::CACHE_KEY . ':' . $categoryId;
        $item = $this->cachePool->getItem($cacheKey);

        if (!$item->isHit()) {
            $treeName = $this->categoryRepository->findTreeName($categoryId) ?? '';

            $item->set($treeName);
            $this->cachePool->save($item);
        }

        return (string) $item->get();
    }

    /**
     * @return array<int, string>
     */
    public function getCategoryTrees(): array
    {
        $result = $this->categoryRepository->findTreeNames();

        foreach ($result as $categoryId => $treeName) {
            $item = $this->cachePool->getItem(self::CACHE_KEY . ':' . $categoryId);
            $item->set($treeName);
            $this->cachePool->save($item);
        }

        return $result;
    }

    /**
     * @param list<int> $categoryIds
     * @return array<int, string>
     */
    public function getCategoryTreesByIds(array $categoryIds): array
    {
        $result = [];
        $missingIds = [];

        foreach ($categoryIds as $categoryId) {
            $cacheKey = self::CACHE_KEY . ':' . $categoryId;
            $item = $this->cachePool->getItem($cacheKey);

            if ($item->isHit()) {
                $result[$categoryId] = (string) $item->get();
                continue;
            }

            $missingIds[] = $categoryId;
        }

        if ($missingIds !== []) {
            $trees = $this->categoryRepository->findTreeNames($missingIds);

            foreach ($trees as $categoryId => $treeName) {
                $result[$categoryId] = $treeName;

                $item = $this->cachePool->getItem(self::CACHE_KEY . ':' . $categoryId);
                $item->set($treeName);
                $this->cachePool->save($item);
            }
        }

        return $result;
    }

    public function evictAllCategoryTrees(): void
    {
        $this->cachePool->clear();
    }
}
