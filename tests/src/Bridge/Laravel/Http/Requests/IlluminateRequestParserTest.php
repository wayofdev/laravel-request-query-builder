<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Tests\Bridge\Laravel\Http\Requests;

use Illuminate\Http\Request;
use WayOfDev\RQL\Bridge\Laravel\Http\Requests\IlluminateRequestParser;
use WayOfDev\RQL\Contracts\ConfigRepository;
use WayOfDev\RQL\Requests\Components\Relation;
use WayOfDev\RQL\Tests\TestCase;

final class IlluminateRequestParserTest extends TestCase
{
    public static function valueDataProvider(): array
    {
        // Format: [Request Input], [Value after parsing], [DataType after parsing]
        return [
            [
                // Example: ?filter[profile.name]=John
                ['filter' => ['profile.name' => 'John']],
                'John',
                'string',
            ],
            [
                // Example: ?filter[profile.name][$eq]=John
                ['filter' => ['profile.name' => ['$eq' => 'John']]],
                'John',
                'string',
            ],
            [
                // Example: ?filter[profile.name][$eq]=$string:John
                ['filter' => ['profile.name' => ['$eq' => '$string:John']]],
                'John',
                'string',
            ],
            [
                // Example: ?filter[profile.name][$eq]=$int:123
                ['filter' => ['profile.name' => ['$eq' => '$int:123']]],
                '123',
                'int',
            ],
            [
                // Example: ?filter[profile.name][$eq]=$unknown:123
                ['filter' => ['profile.name' => ['$eq' => '$unknown:123']]],
                '123',
                'string',
            ],
            [
                // Example: ?filter[profile.name][$eq]=$unknown:John
                ['filter' => ['profile.name' => ['$eq' => '$unknown:John']]],
                'John',
                'string',
            ],
            [
                // Example: ?filter[profile.name][$eq]=$int:some_data:another_data:John
                ['filter' => ['profile.name' => ['$eq' => '$int:some_data:another_data:John']]],
                'some_data:another_data:John',
                'int',
            ],
        ];
    }

    /**
     * Example: ?filter[name]=John&filter[surname]=Doe.
     *
     * @test
     */
    public function it_parses_request(): void
    {
        $request = new Request();
        $request->replace([
            'filter' => [
                'name' => 'John',
                'surname' => 'Doe',
            ],
        ]);

        $collection = (new IlluminateRequestParser(resolve(ConfigRepository::class), $request))->parse();

        $array = $collection->toArray();

        $this::assertEquals('', $array[0]->relation()->toString());
        $this::assertEquals('name', $array[0]->column()->toString());

        $this::assertEquals('', $array[1]->relation());
        $this::assertEquals('surname', $array[1]->column()->toString());
    }

    /**
     * Example: ?filter[profile.name]=John&filter[profile.surname]=Doe.
     *
     * @test
     */
    public function it_gets_relation_and_column_name(): void
    {
        $request = new Request();
        $request->replace([
            'filter' => [
                'profile.name' => 'John',
                'profile.surname' => 'Doe',
            ],
        ]);

        $collection = (new IlluminateRequestParser(resolve(ConfigRepository::class), $request))->parse();
        $array = $collection->toArray();

        $this::assertInstanceOf(Relation::class, $array[0]->relation());

        $this::assertEquals('profile', $array[0]->relation()->toString());
        $this::assertEquals('name', $array[0]->column()->toString());

        $this::assertEquals('profile', $array[1]->relation()->toString());
        $this::assertEquals('surname', $array[1]->column()->toString());
    }

    /**
     * Example: ?filter[name]=$eq:John&filter[surname]=$like:%Doe%.
     *
     * @test
     */
    public function it_should_get_logical_expression(): void
    {
        $request = new Request();
        $request->replace([
            'filter' => [
                'profile.name' => [
                    '$eq' => 'John',
                ],
                'profile.surname' => [
                    '$like' => '%Doe%',
                ],
            ],
        ]);

        $collection = (new IlluminateRequestParser(resolve(ConfigRepository::class), $request))->parse();
        $array = $collection->toArray();

        $this::assertEquals('eq', $array[0]->expression()->toString());
        $this::assertEquals('like', $array[1]->expression()->toString());
    }

    /**
     * @test
     *
     * @dataProvider valueDataProvider
     */
    public function it_should_get_data_type_and_value(array $filter, string $valueToBe, string $dataTypeToBe): void
    {
        $request = (new Request())->replace($filter);
        $collection = (new IlluminateRequestParser(resolve(ConfigRepository::class), $request))->parse();
        $array = $collection->toArray();

        $this::assertEquals($valueToBe, $array[0]->value()->toString());
        $this::assertEquals($dataTypeToBe, $array[0]->dataType()->toString());
    }
}
