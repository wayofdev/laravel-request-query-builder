<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Bridge\Cycle\Processors;

use Cycle\ORM\Select as Builder;
use WayOfDev\RQL\Contracts\ExpressionInterface;
use WayOfDev\RQL\Contracts\ProcessorInterface;
use WayOfDev\RQL\Exceptions\ExpressionException;
use WayOfDev\RQL\ExpressionQueue;
use WayOfDev\RQL\FilterStrategyResolver;

final class CycleProcessor implements ProcessorInterface
{
    private static array $comparisonMethods = [
        'eq',
        'notEq',
        'lt',
        'lte',
        'gt',
        'gte',
        'like',
    ];

    private Builder $builder;

    private FilterStrategyResolver $resolver;

    public static function comparisonMethods(): array
    {
        return self::$comparisonMethods;
    }

    public function __construct(FilterStrategyResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function builder(): Builder
    {
        return $this->builder;
    }

    public function setBuilder(Builder $builder): self
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * @throws ExpressionException
     */
    public function process(ExpressionQueue $exprClasses): Builder
    {
        foreach ($exprClasses as $exprClass) {
            /* @var ExpressionInterface $exprClass */
            $this->builder = $this->resolver
                ->resolve($this, $exprClass)
                ->apply($this, $exprClass);
        }

        return $this->builder;
    }
}
