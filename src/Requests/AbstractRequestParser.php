<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Requests;

use Illuminate\Support\Collection;
use WayOfDev\RQL\Contracts\ConfigRepository;
use WayOfDev\RQL\Contracts\RequestParserInterface;
use WayOfDev\RQL\Requests\Components\Column;
use WayOfDev\RQL\Requests\Components\DataType;
use WayOfDev\RQL\Requests\Components\Expression;
use WayOfDev\RQL\Requests\Components\Relation;
use WayOfDev\RQL\Requests\Components\Value;

abstract class AbstractRequestParser implements RequestParserInterface
{
    protected ConfigRepository $config;

    public function __construct(ConfigRepository $config)
    {
        $this->config = $config;
    }

    protected function parseInput($input): Collection
    {
        $output = collect();

        if (! $input) {
            return $output;
        }

        foreach ($input as $key => $item) {
            $output->push(new RequestParameter(
                Relation::fromRequest($key),
                Column::fromRequest($key),
                Expression::fromRequest(
                    $item,
                    $this->config->defaultExpression(),
                    $this->config->allowedExpressions(),
                    $this->config->filteringPrefix(),
                    $this->config->strictComparison()
                ),
                DataType::fromRequest(
                    $item,
                    $this->config->defaultDataType(),
                    $this->config->allowedDataTypes(),
                    $this->config->filteringPrefix(),
                    $this->config->strictComparison()
                ),
                Value::fromRequest(
                    $item,
                    $this->config->filteringPrefix()
                )
            ));
        }

        return $output;
    }
}
