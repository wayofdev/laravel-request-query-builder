<?php

declare(strict_types=1);

namespace WayOfDev\RQL\App\Requests;

use WayOfDev\RQL\Concerns\HasFilters;
use WayOfDev\RQL\Contracts\CriteriaParameters;
use WayOfDev\RQL\Requests\RequestParameter;

final class QueryUserDto implements CriteriaParameters
{
    use HasFilters;

    public ?RequestParameter $name = null;

    public ?RequestParameter $surname = null;

    public ?RequestParameter $phone = null;
}
