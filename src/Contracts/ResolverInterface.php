<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Contracts;

interface ResolverInterface
{
    public function registerAll(array $filterStrategies): mixed;

    public function register(string $filterStrategy): mixed;

    public function resolve(ProcessorInterface $processor, ExpressionInterface $exprClass): SpecInterface;
}
