<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Bridge\Laravel\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use WayOfDev\RQL\Contracts\ConfigRepository;
use WayOfDev\RQL\Requests\AbstractRequestParser;

final class IlluminateRequestParser extends AbstractRequestParser
{
    private Request $request;

    public function __construct(ConfigRepository $config, Request $request)
    {
        parent::__construct($config);

        $this->request = $request;
    }

    public function parse(): Collection
    {
        $input = $this->queryValue();

        return $this->parseInput($input);
    }

    private function queryValue(): null|string|array
    {
        return $this->request->input(
            $this->config->defaultQueryParameter()
        );
    }
}
