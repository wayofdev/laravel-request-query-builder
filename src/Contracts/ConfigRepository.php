<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Contracts;

interface ConfigRepository
{
    public function defaultQueryParameter(): string;

    public function processor(): array;

    public function processorFQCN(): string;

    public function filteringPrefix(): string;

    public function allowedExpressions(): array;

    public function defaultExpression(): string;

    public function allowedDataTypes(): array;

    public function defaultDataType(): string;

    public function strictComparison(): bool;
}
