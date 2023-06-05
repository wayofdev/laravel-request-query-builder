<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Bridge\Cycle;

use Cycle\ORM\EntityManagerInterface;
use Cycle\ORM\Select;
use Cycle\ORM\Select\Repository as CycleRepository;
use Illuminate\Support\Collection;
use Spiral\Pagination\Paginator as SpiralPaginator;
use Throwable;
use WayOfDev\Paginator\CyclePaginator;
use WayOfDev\RQL\Bridge\Cycle\Concerns\HasCriteria;

/**
 * Repository provides ability to load entities and construct queries.
 *
 * @template TEntity of object
 *
 * @extends CycleRepository<TEntity>
 */
class Repository extends CycleRepository
{
    use HasCriteria;

    /**
     * Create repository linked to one specific selector.
     *
     * @param Select<TEntity> $select
     */
    public function __construct(
        // @phpstan-ignore-next-line
        protected Select $select,
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct($select);
    }

    // @phpstan-ignore-next-line
    public function findAll(array $scope = [], array $orderBy = []): Collection
    {
        $select = $this->applyCriteria($this->select())->where($scope);

        return $this->createCollection(
            $select->fetchAll()
        );
    }

    public function count(string $column = null): int
    {
        $select = $this->applyCriteria($this->select());

        return $select->count($column);
    }

    /**
     * @throws Throwable
     */
    public function persist(object $entity, bool $cascade = true): void
    {
        $this->entityManager->persist(
            $entity,
            $cascade
        );

        $this->entityManager->run();
    }

    public function paginate(int $perPage = 20, int $page = 1, string $pageName = 'page'): CyclePaginator
    {
        $select = $this->applyCriteria($this->select());

        return $this->paginateQuery(
            $select,
            $perPage,
            $page,
            $pageName,
        );
    }

    protected function paginateQuery(Select $query, int $perPage = 20, int $page = 1, string $pageName = 'page'): CyclePaginator
    {
        return new CyclePaginator(
            // @phpstan-ignore-next-line
            (new SpiralPaginator($perPage))->withPage($page)->paginate($query),
            $this->createCollection($query->fetchAll()),
            $pageName,
        );
    }

    protected function createCollection(iterable $items): Collection
    {
        return new Collection($items);
    }
}
