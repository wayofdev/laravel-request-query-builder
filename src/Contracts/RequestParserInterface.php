<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Contracts;

use Illuminate\Support\Collection;

interface RequestParserInterface
{
    public function parse(): Collection;
}
