<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Bridge\Cycle;

interface CriteriaManager
{
    public function pushCriteria(mixed $criteria): object;

    public function popCriteria(mixed $criteria): object;

    public function clearCriteria(): object;
}
