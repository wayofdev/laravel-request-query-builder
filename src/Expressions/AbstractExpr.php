<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Expressions;

use WayOfDev\RQL\Contracts\ExpressionInterface;

use function explode;

abstract class AbstractExpr implements ExpressionInterface
{
    protected static function valueToArray($delimiter, $value): array
    {
        return explode($delimiter, $value);
    }

    abstract public static function create(string $column, mixed $value, ?string $relation): self;

    public function __construct(
        private readonly string $column,
        private readonly mixed $value,
        private readonly string $expression,
        private readonly ?string $operator,
        private readonly ?string $relation
    ) {
    }

    public function column(): string
    {
        return $this->column;
    }

    public function value(): mixed
    {
        return $this->value;
    }

    public function expression(): string
    {
        return $this->expression;
    }

    public function operator(): ?string
    {
        return $this->operator;
    }

    public function relation(): ?string
    {
        return $this->relation;
    }
}
