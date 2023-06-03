<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Bridge\Cycle\Criteria;

use Cycle\ORM\Select;
use WayOfDev\RQL\Exceptions\ExpressionException;
use WayOfDev\RQL\ExpressionQueue;
use WayOfDev\RQL\Expressions\AbstractExpr;
use WayOfDev\RQL\Requests\Components\Column;
use WayOfDev\RQL\Requests\Components\Expression;
use WayOfDev\RQL\Requests\Components\Value;
use WayOfDev\RQL\Requests\RequestParameter;
use WayOfDev\RQL\RQLFactory;

use function ucfirst;

final class FilterBy implements CriteriaInterface
{
    private const BASE_EXPR_NAMESPACE = 'WayOfDev\RQL\Expressions\\';

    /**
     * @var RequestParameter[]
     */
    private array $parameters;

    private RQLFactory $rql;

    private ?ExpressionQueue $queue;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
        $this->queue = new ExpressionQueue();
        $this->rql = resolve(RQLFactory::class);
    }

    /**
     * @throws ExpressionException
     */
    public function apply(Select $select): Select
    {
        foreach ($this->parameters as $parameter) {
            $select = $this->filter($select, $parameter);
        }

        return $select;
    }

    /**
     * @throws ExpressionException
     */
    private function filter(Select $select, RequestParameter $parameter): Select
    {
        $exprClasses = $this->queue->enqueue(
            $this->createExprClass(
                $parameter->expression(),
                $parameter->column(),
                $parameter->value()
            )
        );

        return $this->rql->processor()
            ->setBuilder($select)
            ->process($exprClasses);
    }

    private function createExprClass(Expression $expression, Column $column, Value $value): AbstractExpr
    {
        /** @var AbstractExpr $exprClass */
        $exprClass = self::BASE_EXPR_NAMESPACE . ucfirst($expression->toString()) . 'Expr';

        return $exprClass::create($column->toString(), $value->toString(), null);
    }
}
