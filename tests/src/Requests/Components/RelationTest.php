<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Tests\Requests\Components;

use WayOfDev\RQL\Requests\Components\Relation;
use WayOfDev\RQL\Tests\TestCase;

final class RelationTest extends TestCase
{
    /**
     * Example: ?filter[profile.name]=John.
     *
     * @test
     */
    public function it_should_get_relation_from_column(): void
    {
        $key = 'profile.name';

        $relation = Relation::fromRequest($key);

        self::assertEquals('profile', $relation->toString());
    }

    /**
     * Example: ?filter[name]=John.
     *
     * @test
     */
    public function it_should_return_empty_relation(): void
    {
        $key = 'name';

        $relation = Relation::fromRequest($key);

        self::assertEquals(null, $relation->toString());
    }
}
