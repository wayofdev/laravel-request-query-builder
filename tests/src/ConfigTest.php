<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Tests;

use WayOfDev\RQL\Config;

final class ConfigTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_config(): void
    {
        $configToTest = Config::fromArray(app('config')->get('rql'));

        self::assertEquals('filter', $configToTest->defaultQueryParameter());
        self::assertArrayHasKey('filter_specs', $configToTest->processor());
        self::assertEquals('$', $configToTest->filteringPrefix());
        self::assertEquals(['eq', 'notEq', 'lt', 'lte', 'gt', 'gte', 'like', 'in', 'notIn', 'or', 'between'], $configToTest->allowedExpressions());
        self::assertEquals('eq', $configToTest->defaultExpression());
        self::assertEquals(['string', 'bool', 'int', 'date', 'datetime'], $configToTest->allowedDataTypes());
        self::assertEquals('string', $configToTest->defaultDataType());
        self::assertEquals(false, $configToTest->strictComparison());
    }
}
