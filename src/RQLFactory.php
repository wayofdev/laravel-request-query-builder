<?php

declare(strict_types=1);

namespace WayOfDev\RQL;

use WayOfDev\RQL\Contracts\ConfigRepository;
use WayOfDev\RQL\Contracts\ProcessorInterface;
use WayOfDev\RQL\Contracts\ResolverInterface;

final class RQLFactory
{
    private ConfigRepository $config;

    private ?ProcessorInterface $processor = null;

    public function __construct(ConfigRepository $config, ProcessorInterface $processor = null)
    {
        $this->config = $config;
        $this->processor = $processor;
    }

    public function processor(): ProcessorInterface
    {
        if (null === $this->processor) {
            $this->processor = $this->createProcessor();
        }

        return $this->processor;
    }

    private function createProcessor(): ProcessorInterface
    {
        $class = $this->config->processorFQCN();

        $resolver = $this->createFilterResolver($this->config->processor()['filter_specs']);

        return new $class($resolver);
    }

    private function createFilterResolver(array $filterSpecs): ResolverInterface
    {
        return (new FilterStrategyResolver())
            ->registerAll($filterSpecs);
    }
}
