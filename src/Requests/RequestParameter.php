<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Requests;

use WayOfDev\RQL\Requests\Components\Column;
use WayOfDev\RQL\Requests\Components\DataType;
use WayOfDev\RQL\Requests\Components\Expression;
use WayOfDev\RQL\Requests\Components\Relation;
use WayOfDev\RQL\Requests\Components\Value;

final readonly class RequestParameter
{
    public function __construct(
        private Relation $relation,
        private Column $column,
        private Expression $expression,
        private DataType $dataType,
        private Value $value
    ) {
    }

    public function relation(): Relation
    {
        return $this->relation;
    }

    public function column(): Column
    {
        return $this->column;
    }

    public function expression(): Expression
    {
        return $this->expression;
    }

    public function dataType(): DataType
    {
        return $this->dataType;
    }

    public function value(): Value
    {
        return $this->value;
    }
}
