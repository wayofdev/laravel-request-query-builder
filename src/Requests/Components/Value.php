<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Requests\Components;

use Illuminate\Support\Str;

use function array_shift;
use function is_array;
use function str_contains;
use function strpos;
use function substr;

final class Value
{
    public static function fromRequest(string|array $value, string $prefix): self
    {
        return new self($value, $prefix);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function assertValidValue(string|array $value, string $prefix): string
    {
        $value = $this->extractValue($value);

        if (Str::startsWith($value, [$prefix]) && str_contains($value, ':')) {
            $lastColonPosition = strpos($value, ':');

            return substr($value, $lastColonPosition + 1);
        }

        return $value;
    }

    private function __construct(private string|array $value, string $prefix)
    {
        $this->value = $this->assertValidValue($value, $prefix);
    }

    private function extractValue(string|array $value): string
    {
        if (! is_array($value)) {
            return $value;
        }

        return array_shift($value);
    }
}
