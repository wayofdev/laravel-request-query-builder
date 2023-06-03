<?php

declare(strict_types=1);

namespace WayOfDev\RQL\App\Requests;

use WayOfDev\RQL\Bridge\Laravel\Http\Requests\ApiRequest;

final class QueryFormRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => [],
            'surname' => [],
            'phone' => [],
        ];
    }
}
