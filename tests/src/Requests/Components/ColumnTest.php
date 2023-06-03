<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Tests\Requests\Components;

use WayOfDev\RQL\Requests\Components\Column;
use WayOfDev\RQL\Tests\TestCase;

final class ColumnTest extends TestCase
{
    /**
     * Example: ?filter[profile.name]=John.
     *
     * @test
     */
    public function it_should_get_column_when_relation_also_exists(): void
    {
        $key = 'profile.name';

        $column = Column::fromRequest($key);

        self::assertEquals('name', $column->toString());
    }

    /**
     * Example: ?filter[name]=John.
     *
     * @test
     */
    public function it_should_get_column_when_relation_is_empty(): void
    {
        $key = 'name';

        $column = Column::fromRequest($key);

        self::assertEquals('name', $column->toString());
    }
}
