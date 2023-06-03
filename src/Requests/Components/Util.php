<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Requests\Components;

use Illuminate\Support\Str;
use RuntimeException;

use function in_array;
use function strlen;
use function substr;

trait Util
{
    private function isValid(string $parameter, array $allowedList, bool $strict = false): bool
    {
        if (! in_array($parameter, $allowedList, true)) {
            if ($strict) {
                throw new RuntimeException('Invalid/Not allowed parameter passed.');
            }

            return false;
        }

        return true;
    }

    private function trim(string $prefix, $parameter)
    {
        if (Str::startsWith($parameter, $prefix)) {
            return substr($parameter, strlen($prefix));
        }

        return $parameter;
    }
}
