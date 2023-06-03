<?php

declare(strict_types=1);

namespace WayOfDev\RQL;

use Illuminate\Support\Arr;
use RuntimeException;
use WayOfDev\RQL\Contracts\ConfigRepository;
use WayOfDev\RQL\Exceptions\MissingRequiredAttributes;

use function array_diff;
use function array_keys;
use function class_exists;
use function implode;

final class Config implements ConfigRepository
{
    private const REQUIRED_FIELDS = [
        'default_query_parameter',
        'default_processor',
        'processors',
        'filtering',
    ];

    public static function fromArray(array $config): self
    {
        $missingAttributes = array_diff(array_keys($config), self::REQUIRED_FIELDS);

        if ([] !== $missingAttributes) {
            throw MissingRequiredAttributes::fromArray(
                implode(',', $missingAttributes)
            );
        }

        $processor = Arr::get($config, "processors.{$config['default_processor']}");

        return new self(
            $config['default_query_parameter'],
            $processor,
            Arr::get($config, 'filtering.filtering_prefix'),
            Arr::get($config, 'filtering.allowed_expressions'),
            Arr::get($config, 'filtering.default_expression'),
            Arr::get($config, 'filtering.allowed_data_types'),
            Arr::get($config, 'filtering.default_data_type'),
            Arr::get($config, 'filtering.strict_comparison')
        );
    }

    public function __construct(
        private readonly string $defaultQueryParameter,
        private readonly array $processor,
        private readonly string $filteringPrefix,
        private readonly array $allowedExpressions,
        private readonly string $defaultExpression,
        private readonly array $allowedDataTypes,
        private readonly string $defaultDataType,
        private readonly bool $strictComparison
    ) {
    }

    public function defaultQueryParameter(): string
    {
        return $this->defaultQueryParameter;
    }

    public function processor(): array
    {
        return $this->processor;
    }

    public function processorFQCN(): string
    {
        $class = $this->processor['class'];

        if (! class_exists($class)) {
            throw new RuntimeException('Processor class "' . $class . '" does not exist!');
        }

        return $class;
    }

    public function filteringPrefix(): string
    {
        return $this->filteringPrefix;
    }

    public function allowedExpressions(): array
    {
        return $this->allowedExpressions;
    }

    public function defaultExpression(): string
    {
        return $this->defaultExpression;
    }

    public function allowedDataTypes(): array
    {
        return $this->allowedDataTypes;
    }

    public function defaultDataType(): string
    {
        return $this->defaultDataType;
    }

    public function strictComparison(): bool
    {
        return $this->strictComparison;
    }
}
