<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Expressions;

use WayOfDev\RQL\Exceptions\ExpressionException;

use function array_filter;
use function count;
use function is_string;
use function trim;

final class OrExpr extends AbstractExpr
{
    /**
     * @throws ExpressionException
     */
    public static function create(string $column, mixed $value, ?string $relation): self
    {
        if (is_string($value)) {
            $value = array_filter(
                self::valueToArray('|', trim($value))
            );
        }

        if (count($value) < 2) {
            throw new ExpressionException('The number of "values" must be greater than one');
        }

        return new self($column, $value, 'or', null, $relation);
    }
}
