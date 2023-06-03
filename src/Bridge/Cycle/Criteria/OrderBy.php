<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Bridge\Cycle\Criteria;

use Cycle\ORM\Select;
use WayOfDev\RQL\Bridge\Cycle\Exceptions\ValidationException;
use WayOfDev\RQL\Requests\Components\OrderBy as Order;

use function preg_match;

final class OrderBy implements CriteriaInterface
{
    private const ALLOWED_TO_CONTAIN = '/^[a-z0-9\.\_\-]+$/i';

    public function __construct(private ?Order $orderBy)
    {
    }

    /**
     * @throws ValidationException
     */
    public function apply(Select $select): Select
    {
        if (null === $this->orderBy) {
            return $select;
        }

        if (! preg_match(self::ALLOWED_TO_CONTAIN, $this->orderBy->column())) {
            throw new ValidationException('OrderBy query parameter contains illegal characters.');
        }

        return $select->orderBy($this->orderBy->column(), $this->orderBy->direction()->value);
    }
}
