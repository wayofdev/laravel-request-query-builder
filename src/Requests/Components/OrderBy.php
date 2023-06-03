<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Requests\Components;

use WayOfDev\RQL\Enums\SortingDirection;

use function explode;

final class OrderBy
{
    private string $column;

    private SortingDirection $direction;

    public static function fromRequest(string $value): self
    {
        return new self($value);
    }

    public function column(): string
    {
        return $this->column;
    }

    public function direction(): SortingDirection
    {
        return $this->direction;
    }

    private function __construct(string $value)
    {
        [$column, $direction] = explode(',', $value);

        $this->column = $column;
        $this->direction = SortingDirection::from($direction);
    }
}
