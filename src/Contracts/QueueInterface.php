<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Contracts;

use WayOfDev\RQL\ExpressionQueue;
use WayOfDev\RQL\Expressions\AbstractExpr;

interface QueueInterface
{
    public function enqueue(AbstractExpr $value): ExpressionQueue;
}
