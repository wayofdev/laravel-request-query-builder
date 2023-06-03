<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Bridge\Laravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Laminas\Hydrator\ReflectionHydrator;
use WayOfDev\RQL\Contracts\ConfigRepository;
use WayOfDev\RQL\Requests\Components\Limit;
use WayOfDev\RQL\Requests\Components\OrderBy;

abstract class ApiRequest extends FormRequest
{
    public function toDto(object $dto): object
    {
        $config = resolve(ConfigRepository::class);
        $hydrator = new ReflectionHydrator();
        $collection = (new IlluminateRequestParser($config, $this))->parse();
        $data = [];

        foreach ($collection as $item) {
            $data[$item->column()->toString()] = $item;
        }

        $data['limit'] = null !== $this->get('limit') ? Limit::fromRequest($this->get('limit')) : null;
        $data['orderBy'] = null !== $this->get('order_by') ? OrderBy::fromRequest($this->get('order_by')) : null;

        $hydrator->hydrate($data, $dto);

        return $dto;
    }
}
