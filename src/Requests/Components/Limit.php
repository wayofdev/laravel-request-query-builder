<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Requests\Components;

final class Limit
{
    private int $value;

    public static function fromRequest(string $value): self
    {
        return new self($value);
    }

    public function toString(): string
    {
        return (string) $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function value(): int
    {
        return $this->value;
    }

    private function assertValidValue(string $value): int
    {
        return (int) $value;
    }

    private function __construct(string $value)
    {
        $this->value = $this->assertValidValue($value);
    }
}
