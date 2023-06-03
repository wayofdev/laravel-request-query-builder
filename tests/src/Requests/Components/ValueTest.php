<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Tests\Requests\Components;

use WayOfDev\RQL\Requests\Components\Value;
use WayOfDev\RQL\Tests\TestCase;

final class ValueTest extends TestCase
{
    /**
     * Example: ?filter[name]=John.
     *
     * @test
     */
    public function it_should_get_simple_value(): void
    {
        $value = Value::fromRequest('John', '$');

        self::assertEquals('John', $value->toString());
    }

    /**
     * Example: ?filter[name]=$string:John.
     *
     * @test
     */
    public function it_should_get_value_when_string_data_type_attached(): void
    {
        $value = Value::fromRequest('$string:John', '$');

        self::assertEquals('John', $value->toString());
    }

    /**
     * Example: ?filter[name][$eq]=$int:John.
     *
     * @test
     */
    public function it_should_get_value_when_int_data_type_attached(): void
    {
        $value = Value::fromRequest('$int:John', '$');

        self::assertEquals('John', $value->toString());
    }

    /**
     * Example: ?filter[name]=$unknown:John.
     *
     * @test
     */
    public function it_should_get_value_when_unknown_data_type_attached(): void
    {
        $value = Value::fromRequest('$unknown:John', '$');

        self::assertEquals('John', $value->toString());
    }

    /**
     * Example: ?filter[name]=$int:some_data:another_data:John.
     *
     * @test
     */
    public function it_should_get_value_only_when_valid_prefix_added(): void
    {
        $value = Value::fromRequest('$int:some_data:another_data:John', '$');

        self::assertEquals('some_data:another_data:John', $value->toString());
    }
}
