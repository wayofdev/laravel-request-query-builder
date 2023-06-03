<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Contracts;

interface SpecInterface
{
    public function isSatisfiedBy(ProcessorInterface $processor, ExpressionInterface $exprClass): bool;

    public function apply(ProcessorInterface $processor, ExpressionInterface $exprClass);
}
