<?php

declare(strict_types=1);

namespace WayOfDev\RQL;

use WayOfDev\RQL\Contracts\ExpressionInterface;
use WayOfDev\RQL\Contracts\ProcessorInterface;
use WayOfDev\RQL\Contracts\ResolverInterface;
use WayOfDev\RQL\Contracts\SpecInterface;
use WayOfDev\RQL\Exceptions\ExpressionException;

use function get_class;

final class FilterStrategyResolver implements ResolverInterface
{
    /**
     * @var SpecInterface[]
     */
    private array $applicableStrategies;

    public function registerAll(array $filterStrategies): self
    {
        foreach ($filterStrategies as $strategy) {
            $this->register($strategy);
        }

        return $this;
    }

    public function register(string $filterStrategy): self
    {
        $this->applicableStrategies[] = new $filterStrategy();

        return $this;
    }

    /**
     * @throws ExpressionException
     */
    public function resolve(ProcessorInterface $processor, ExpressionInterface $exprClass): SpecInterface
    {
        foreach ($this->applicableStrategies as $strategy) {
            if ($strategy->isSatisfiedBy($processor, $exprClass)) {
                return $strategy;
            }
        }

        throw new ExpressionException(
            'Can\'t find how to apply operator defined in class: ' . get_class($exprClass)
        );
    }
}
