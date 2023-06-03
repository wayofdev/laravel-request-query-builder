<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Bridge\Cycle\Processors\Conditions;

use Cycle\ORM\Select as Builder;
use WayOfDev\RQL\Bridge\Cycle\Processors\CycleProcessor;
use WayOfDev\RQL\Contracts\ExpressionInterface;
use WayOfDev\RQL\Contracts\ProcessorInterface;
use WayOfDev\RQL\Contracts\SpecInterface;
use WayOfDev\RQL\Expressions\OrExpr;

final class ApplyOr implements SpecInterface
{
    public function isSatisfiedBy(ProcessorInterface $processor, ExpressionInterface $exprClass): bool
    {
        if (! $exprClass instanceof OrExpr) {
            return false;
        }

        return true;
    }

    public function apply(ProcessorInterface $processor, ExpressionInterface $exprClass): Builder
    {
        /* @var CycleProcessor $processor */
        return $processor->builder()->where(static function (Builder\QueryBuilder $builder) use ($exprClass) {
            $values = $exprClass->value();

            foreach ($values as $value) {
                $builder->orWhere($exprClass->column(), $value);
            }

            return $builder;
        });
    }
}
