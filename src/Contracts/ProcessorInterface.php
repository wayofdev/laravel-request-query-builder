<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Contracts;

use Cycle\ORM\Select as Builder;
use WayOfDev\RQL\ExpressionQueue;

interface ProcessorInterface
{
    public function process(ExpressionQueue $exprClasses);

    public function setBuilder(Builder $builder);

    public function builder();
}
