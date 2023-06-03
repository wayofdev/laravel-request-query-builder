<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Contracts;

interface ExpressionInterface
{
    public function relation(): ?string;

    public function column(): string;

    public function value(): mixed;

    public function expression(): string;

    public function operator(): ?string;
}
