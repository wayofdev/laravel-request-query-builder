<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Tests\Requests\Components;

use WayOfDev\RQL\Requests\Components\DataType;
use WayOfDev\RQL\Tests\TestCase;

final class DataTypeTest extends TestCase
{
    /**
     * Examples:
     *      ?filter[name]=John
     *      ?filter[name]=$string:John
     *      ?filter[active]=$bool:true
     *      ?filter[age]=$int:23
     *      ?filter[day]=$date:2013-09-23
     *      ?filter[updated_at]=$datetime:2013-09-23 09:12:23.
     */
    public function dataTypeProvider(): array
    {
        return [
            ['John', 'string'],
            ['string:John', 'string'],
            ['bool:true', 'bool'],
            ['int:123', 'int'],
            ['date:2013-09-23', 'date'],
            ['datetime:2013-09-23 09:12:23', 'datetime'],
            ['string:John', 'string'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider dataTypeProvider
     */
    public function it_should_get_default_data_type_as_it_is_not_defined_in_request(
        string $dataTypeInput,
        string $dataTypeOutput
    ): void {
        $value = DataType::fromRequest(
            $dataTypeInput,
            'string',
            ['string', 'bool', 'int', 'date', 'datetime'],
            '$',
            false
        );

        self::assertEquals($dataTypeOutput, $value->toString());
    }
}
