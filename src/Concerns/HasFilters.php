<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Concerns;

use WayOfDev\RQL\Requests\Components\Limit;
use WayOfDev\RQL\Requests\Components\OrderBy;

trait HasFilters
{
    public ?OrderBy $orderBy = null;

    public ?Limit $limit = null;

    public function orderBy(): ?OrderBy
    {
        return $this->orderBy;
    }

    public function limit(): ?Limit
    {
        return $this->limit;
    }
}
