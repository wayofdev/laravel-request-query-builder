<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Requests\Components;

use function str_contains;
use function strrpos;
use function substr;

final class Relation
{
    public static function fromRequest(string $value): self
    {
        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function assertValidValue(string $value): string
    {
        if (str_contains($value, '.')) {
            $lastDotPosition = strrpos($value, '.');

            return substr($value, 0, $lastDotPosition);
        }

        return '';
    }

    private function __construct(private ?string $value)
    {
        $this->value = $this->assertValidValue($value);
    }
}
