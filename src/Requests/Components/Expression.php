<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Requests\Components;

use function is_array;
use function key;

final class Expression
{
    use Util;

    public static function fromRequest(
        string|array $value,
        string $default,
        array $allowedExpressions,
        string $filteringPrefix,
        bool $strictComparison
    ): self {
        return new self($value, $default, $allowedExpressions, $filteringPrefix, $strictComparison);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function assertValidValue(
        string|array $value,
        string $default,
        array $allowedExpressions,
        string $filteringPrefix,
        bool $strictComparison
    ): string {
        $expression = (! is_array($value)) ? $value : key($value);
        $expression = $this->trim($filteringPrefix, $expression);

        if (! $this->isValid($expression, $allowedExpressions, $strictComparison)) {
            return $default;
        }

        return $expression;
    }

    private function __construct(
        private string|array $value,
        string $default,
        array $allowedExpressions,
        string $filteringPrefix,
        bool $strictComparison
    ) {
        $this->value = $this->assertValidValue($value, $default, $allowedExpressions, $filteringPrefix, $strictComparison);
    }
}
