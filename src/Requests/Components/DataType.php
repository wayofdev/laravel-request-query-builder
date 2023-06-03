<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Requests\Components;

use function array_shift;
use function is_array;
use function str_contains;
use function strpos;
use function substr;

final class DataType
{
    use Util;

    public static function fromRequest(
        string|array $value,
        string $default,
        array $allowedDataTypes,
        string $filteringPrefix,
        bool $strictComparison
    ): self {
        return new self($value, $default, $allowedDataTypes, $filteringPrefix, $strictComparison);
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
        array $allowedDataTypes,
        string $filteringPrefix,
        bool $strictComparison
    ): string {
        $value = $this->extractValue($value);

        if (str_contains($value, ':')) {
            $lastColonPosition = strpos($value, ':');

            $parsedDataType = substr($value, 0, $lastColonPosition);
            $parsedDataType = $this->trim($filteringPrefix, $parsedDataType);

            if (! $this->isValid($parsedDataType, $allowedDataTypes, $strictComparison)) {
                return $default;
            }

            return $parsedDataType;
        }

        return $default;
    }

    private function __construct(
        private string|array $value,
        string $default,
        array $allowedDataTypes,
        string $filteringPrefix,
        bool $strictComparison
    ) {
        $this->value = $this->assertValidValue($value, $default, $allowedDataTypes, $filteringPrefix, $strictComparison);
    }

    private function extractValue(string|array $value): string
    {
        if (! is_array($value)) {
            return $value;
        }

        return array_shift($value);
    }
}
