<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Bridge\Cycle\Criteria;

use Cycle\ORM\Select;
use WayOfDev\RQL\Requests\Components\Limit;

final class LimitBy implements CriteriaInterface
{
    public function __construct(private readonly ?Limit $limit)
    {
    }

    public function apply(Select $select): Select
    {
        if (null === $this->limit) {
            return $select;
        }

        $count = ($this->limit->value() <= 100) ? $this->limit->value() : 100;

        return $select->limit($count);
    }
}
