<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Tests\Requests\Components;

use WayOfDev\RQL\Requests\Components\Expression;
use WayOfDev\RQL\Tests\TestCase;

final class ExpressionTest extends TestCase
{
    /**
     * Examples:
     *      ?filter[name]=John
     *      ?filter[name][$eq]=John
     *      ?filter[name][$notEq]=John
     *      ?filter[active][$lt]=20
     *      ?filter[active][$lte]=20
     *      ?filter[active][$gt]=20
     *      ?filter[active][$gte]=20
     *      ?filter[active][$like]=20.
     */
    public function dataTypeProvider(): array
    {
        return [
            [
                '', 'eq',
            ],
            [
                ['eq' => 'John'], 'eq',
            ],
            [
                ['notEq' => 'John'], 'notEq',
            ],
            [
                ['lt' => 'John'], 'lt',
            ],
            [
                ['lte' => 'John'], 'lte',
            ],
            [
                ['gt' => 'John'], 'gt',
            ],
            [
                ['gte' => 'John'], 'gte',
            ],
            [
                ['like' => 'John'], 'like',
            ],
            [
                ['in' => 'John'], 'in',
            ],
            [
                ['notIn' => 'John'], 'notIn',
            ],
            [
                ['or' => 'John'], 'or',
            ],
            [
                ['between' => 'John'], 'between',
            ],
        ];
    }

    /**
     * @test
     *
     * @dataProvider dataTypeProvider
     */
    public function it_should_get_default_data_type_as_it_is_not_defined_in_request(
        mixed $expInput,
        string $expOutput
    ): void {
        $value = Expression::fromRequest(
            $expInput,
            'eq',
            ['eq', 'notEq', 'lt', 'lte', 'gt', 'gte', 'like', 'in', 'notIn', 'or', 'between'],
            '$',
            false
        );

        self::assertEquals($expOutput, $value->toString());
    }
}
