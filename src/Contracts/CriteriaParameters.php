<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Contracts;

use WayOfDev\RQL\Requests\Components\Limit;
use WayOfDev\RQL\Requests\Components\OrderBy;

interface CriteriaParameters
{
    public function orderBy(): ?OrderBy;

    public function limit(): ?Limit;
}
