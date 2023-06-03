<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Bridge\Cycle\Concerns;

use Cycle\ORM\Select;
use Illuminate\Support\Collection;
use RuntimeException;
use WayOfDev\RQL\Bridge\Cycle\Criteria\CriteriaInterface;

use function get_class;
use function is_object;
use function is_string;

trait HasCriteria
{
    protected ?Collection $criteria = null;

    public function setCriteria(): void
    {
        if (! isset($this->criteria)) {
            $this->criteria = collect();
        }
    }

    public function criteria(): Collection
    {
        return $this->criteria;
    }

    public function pushCriteria(mixed $criteria): self
    {
        if (is_string($criteria)) {
            $criteria = new $criteria();
        }

        if (! $criteria instanceof CriteriaInterface) {
            throw new RuntimeException(
                'Class ' . get_class($criteria) . ' must be an instance of ' . CriteriaInterface::class
            );
        }

        $this->setCriteria();

        $this->criteria()->push($criteria);

        return $this;
    }

    public function popCriteria(mixed $criteria): self
    {
        $this->criteria = $this->criteria->reject(static function ($value) use ($criteria): bool {
            if (is_object($value) && is_string($criteria)) {
                return get_class($value) === $criteria;
            }

            if (is_string($value) && is_object($criteria)) {
                return get_class($criteria) === $value;
            }

            return get_class($value) === get_class($criteria);
        });

        return $this;
    }

    public function clearCriteria(): self
    {
        $this->criteria = new Collection();

        return $this;
    }

    protected function applyCriteria(Select $select): Select
    {
        $this->setCriteria();

        $criteria = $this->criteria();

        if ($criteria->count() === 0) {
            return $select;
        }

        foreach ($criteria as $value) {
            if ($value instanceof CriteriaInterface) {
                $select = $value->apply($select);
            }
        }

        return $select;
    }
}
