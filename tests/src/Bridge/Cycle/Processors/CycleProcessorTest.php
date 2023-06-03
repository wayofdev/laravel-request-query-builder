<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Tests\Bridge\Cycle\Processors;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select as Builder;
use WayOfDev\RQL\App\Entities\User;
use WayOfDev\RQL\App\Repositories\UserRepository;
use WayOfDev\RQL\Bridge\Cycle\Processors\CycleProcessor;
use WayOfDev\RQL\Contracts\ProcessorInterface;
use WayOfDev\RQL\Exceptions\ExpressionException;
use WayOfDev\RQL\ExpressionQueue;
use WayOfDev\RQL\Expressions\AbstractExpr;
use WayOfDev\RQL\RQLFactory;
use WayOfDev\RQL\Tests\TestCase;

use function ucfirst;

final class CycleProcessorTest extends TestCase
{
    private ?ExpressionQueue $queue = null;

    private ?Builder $builder = null;

    private CycleProcessor $processor;

    public function setUp(): void
    {
        parent::setUp();

        $orm = app(ORMInterface::class);

        $this->queue = new ExpressionQueue();

        /** @var UserRepository $repository */
        $repository = $orm->getRepository(User::class);
        $this->builder = $repository->select();

        $this->processor = $this->app->make(ProcessorInterface::class)->setBuilder($this->builder);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->builder = null;
        $this->queue = null;
    }

    /**
     * @test
     *
     * @throws ExpressionException
     */
    public function it_tests_using_di(): void
    {
        $exprClasses = $this->queue->enqueue(
            $this->createExprClass('between', 'id', '2,5')
        );

        /** @var RQLFactory $rql */
        $rql = app(RQLFactory::class);
        $rql->processor()->process($exprClasses);

        $query = $this->processor->process($exprClasses);

        self::assertStringContainsString(
            'WHERE "user"."id" BETWEEN ? AND ?',
            $query->sqlStatement()
        );
    }

    /**
     * @test
     *
     * @throws ExpressionException
     */
    public function it_tests_expr_between(): void
    {
        $exprClasses = $this->queue->enqueue(
            $this->createExprClass('between', 'id', '2,5')
        );

        $query = $this->processor->process($exprClasses);

        self::assertStringContainsString(
            'WHERE "user"."id" BETWEEN ? AND ?',
            $query->sqlStatement()
        );
    }

    /**
     * @test
     *
     * @throws ExpressionException
     */
    public function it_tests_expr_eq(): void
    {
        $exprClasses = $this->queue->enqueue(
            $this->createExprClass('eq', 'name', 'John')
        );

        $query = $this->processor->process($exprClasses);

        self::assertStringContainsString(
            'WHERE "user"."name" = ?',
            $query->sqlStatement()
        );
    }

    /**
     * @test
     *
     * @throws ExpressionException
     */
    public function it_tests_expr_not_eq(): void
    {
        $exprClasses = $this->queue->enqueue(
            $this->createExprClass('notEq', 'name', 'John')
        );

        $query = $this->processor->process($exprClasses);

        self::assertStringContainsString(
            'WHERE "user"."name" != ?',
            $query->sqlStatement()
        );
    }

    /**
     * @test
     *
     * @throws ExpressionException
     */
    public function it_tests_expr_lt(): void
    {
        $exprClasses = $this->queue->enqueue(
            $this->createExprClass('lt', 'updated_at', '2019-01-01 14:00:23')
        );

        $query = $this->processor->process($exprClasses);

        self::assertStringContainsString(
            'WHERE "user"."updated_at" < ?',
            $query->sqlStatement()
        );
    }

    /**
     * @test
     *
     * @throws ExpressionException
     */
    public function it_tests_expr_lte(): void
    {
        $exprClasses = $this->queue->enqueue(
            $this->createExprClass('lte', 'updated_at', '2019-01-01 14:00:23')
        );

        $query = $this->processor->process($exprClasses);

        self::assertStringContainsString(
            'WHERE "user"."updated_at" <= ?',
            $query->sqlStatement()
        );
    }

    /**
     * @test
     *
     * @throws ExpressionException
     */
    public function it_tests_expr_gt(): void
    {
        $exprClasses = $this->queue->enqueue(
            $this->createExprClass('gt', 'updated_at', '2019-01-01 14:00:23')
        );

        $query = $this->processor->process($exprClasses);

        self::assertStringContainsString(
            'WHERE "user"."updated_at" > ?',
            $query->sqlStatement()
        );
    }

    /**
     * @test
     *
     * @throws ExpressionException
     */
    public function it_tests_expr_gte(): void
    {
        $exprClasses = $this->queue->enqueue(
            $this->createExprClass('gte', 'updated_at', '2019-01-01 14:00:23')
        );

        $query = $this->processor->process($exprClasses);

        self::assertStringContainsString(
            'WHERE "user"."updated_at" >= ?',
            $query->sqlStatement()
        );
    }

    /**
     * @test
     *
     * @throws ExpressionException
     */
    public function it_tests_expr_like(): void
    {
        $exprClasses = $this->queue->enqueue(
            $this->createExprClass('like', 'name', '%RandomName%')
        );

        $query = $this->processor->process($exprClasses);

        self::assertStringContainsString(
            'WHERE "user"."name" LIKE ?',
            $query->sqlStatement()
        );
    }

    /**
     * @test
     *
     * @throws ExpressionException
     */
    public function it_tests_expr_in(): void
    {
        $exprClasses = $this->queue->enqueue(
            $this->createExprClass('in', 'id', '2,5,6,227')
        );

        $query = $this->processor->process($exprClasses);

        $this::assertStringContainsString(
            'WHERE "user"."id" IN (? ,? ,? ,?)',
            $query->sqlStatement()
        );
    }

    /**
     * @test
     *
     * @throws ExpressionException
     */
    public function it_tests_expr_not_in(): void
    {
        $exprClasses = $this->queue->enqueue(
            $this->createExprClass('notIn', 'id', '2,5,6,227')
        );

        $query = $this->processor->process($exprClasses);

        $this::assertStringContainsString(
            'WHERE "user"."id" NOT IN (? ,? ,? ,?)',
            $query->sqlStatement()
        );
    }

    /**
     * @test
     *
     * @throws ExpressionException
     */
    public function it_tests_expr_or(): void
    {
        $exprClasses = $this->queue->enqueue(
            $this->createExprClass('or', 'id', '2|5|6')
        );

        $query = $this->processor->process($exprClasses);

        $this::assertStringContainsString(
            'WHERE ("user"."id" = ? OR "user"."id" = ? OR "user"."id" = ?  )',
            $query->sqlStatement()
        );
    }

    private function createExprClass(string $expression, string $column, mixed $value): AbstractExpr
    {
        $namespace = 'WayOfDev\RQL\Expressions\\';
        /** @var AbstractExpr $exprClass */
        $exprClass = $namespace . ucfirst($expression) . 'Expr';

        return $exprClass::create($column, $value, null);
    }
}
