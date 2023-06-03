<?php

declare(strict_types=1);

namespace WayOfDev\RQL;

use WayOfDev\RQL\Contracts\CriteriaParameters;
use WayOfDev\RQL\Requests\Components\Limit;
use WayOfDev\RQL\Requests\Components\OrderBy;
use WayOfDev\RQL\Requests\RequestParameter;

use function array_filter;
use function get_object_vars;

final class CriteriaAggregate
{
    private ?array $attributes = null;

    public function __construct(private readonly CriteriaParameters $dto)
    {
        $this->attributes = get_object_vars($dto);
    }

    public function parameters(): array
    {
        return array_filter($this->attributes, static function ($value) {
            return $value instanceof RequestParameter;
        });
    }

    public function orderBy(): ?OrderBy
    {
        return $this->dto->orderBy();
    }

    public function limit(): ?Limit
    {
        return $this->dto->limit();
    }
}
