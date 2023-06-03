<?php

declare(strict_types=1);

namespace WayOfDev\RQL;

use SplQueue;
use WayOfDev\RQL\Contracts\ExpressionInterface;
use WayOfDev\RQL\Contracts\QueueInterface;
use WayOfDev\RQL\Exceptions\ExpressionException;

use function sprintf;

final class ExpressionQueue extends SplQueue implements QueueInterface
{
    /**
     * @throws ExpressionException
     */
    public function enqueue(mixed $value): self
    {
        if (! $value instanceof ExpressionInterface) {
            throw new ExpressionException(sprintf(
                'The $value variable is not an instance of %s.',
                ExpressionInterface::class
            ));
        }

        parent::enqueue($value);

        return $this;
    }
}
