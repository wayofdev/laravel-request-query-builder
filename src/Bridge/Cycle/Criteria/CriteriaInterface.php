<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Bridge\Cycle\Criteria;

use Cycle\ORM\Select;

interface CriteriaInterface
{
    public function apply(Select $select): Select;
}
