<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Expressions;

final class NotInExpr extends AbstractExpr
{
    public static function create(string $column, mixed $value, ?string $relation): self
    {
        return new self($column, self::valueToArray(',', $value), 'notIn', 'not in', $relation);
    }
}
