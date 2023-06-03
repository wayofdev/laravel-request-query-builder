<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Tests;

use Cycle\ORM\ORMInterface;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Throwable;
use WayOfDev\Cycle\Repository;
use WayOfDev\RQL\App\Entities\User;
use WayOfDev\RQL\App\Repositories\UserRepository;
use WayOfDev\RQL\App\Requests\QueryFormRequest;
use WayOfDev\RQL\App\Requests\QueryUserDto;
use WayOfDev\RQL\Bridge\Cycle\Criteria\FilterBy;
use WayOfDev\RQL\Bridge\Cycle\Criteria\LimitBy;
use WayOfDev\RQL\Bridge\Cycle\Criteria\OrderBy;
use WayOfDev\RQL\CriteriaAggregate;

final class FullCycleTest extends TestCase
{
    private ORMInterface $orm;

    /**
     * @throws Throwable
     */
    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('cycle:migrate:init');
        Artisan::call('cycle:migrate', ['--force' => true]);
        Artisan::call('cycle:orm:migrate', ['--run' => true]);

        $this->orm = app(ORMInterface::class);

        /** @var Repository $userRepository */
        $userRepository = $this->orm->getRepository(User::class);

        $faker = Container::getInstance()->make(Generator::class);

        $users = Collection::times(20)->map(function ($number) use ($faker) {
            return new User(
                email: $faker->email(),
                username: $faker->userName(),
                name: $faker->firstName(),
                surname: $faker->lastName(),
                company: $faker->company(),
            );
        });

        $users->each(function (User $user) use ($userRepository): void {
            $userRepository->persist($user, false);
        });
    }

    /**
     * @test
     */
    public function it_should_create_request_and_filter_data(): void
    {
        /** @var UserRepository $repository */
        $repository = $this->orm->getRepository(User::class);

        /** @var User $randomUser */
        $randomUser = $repository->findOne();

        $request = new QueryFormRequest();
        $request->replace([
            'filter' => [
                'surname' => $randomUser->getSurname(),
            ],
        ]);
        $dto = $request->toDto(new QueryUserDto());
        $criteria = new CriteriaAggregate($dto);

        $repository->pushCriteria(new FilterBy($criteria->parameters()));
        $users = $repository->findAll();

        self::assertEquals($randomUser->getId(), $users->first()->getId());
    }

    /**
     * @test
     */
    public function it_should_apply_order_by(): void
    {
        /** @var UserRepository $repository */
        $repository = $this->orm->getRepository(User::class);

        $request = new QueryFormRequest();
        $request->replace([
            'order_by' => 'name,asc',
        ]);
        $dto = $request->toDto(new QueryUserDto());
        $criteria = new CriteriaAggregate($dto);

        $repository->pushCriteria(new OrderBy($criteria->orderBy()));

        /** @var Collection<User> $users */
        $users = $repository->findAll();

        /** @var UserRepository $cleanRepository */
        $cleanRepository = $this->orm->getRepository(User::class);

        $randomUsers = $cleanRepository->select()
            ->orderBy('name', 'asc')
            ->limit(10)
            ->fetchAll();

        /** @var User $user */
        $user = $users->first();

        self::assertEquals($randomUsers[0]->getName(), $users->first()->getName());
    }

    /**
     * @test
     */
    public function it_should_limit_to_five_results(): void
    {
        /** @var UserRepository $repository */
        $repository = $this->orm->getRepository(User::class);

        $request = new QueryFormRequest();
        $request->replace([
            'limit' => '5',
            'order_by' => 'name,asc',
        ]);
        $dto = $request->toDto(new QueryUserDto());
        $criteria = new CriteriaAggregate($dto);

        $repository->pushCriteria(new LimitBy($criteria->limit()));

        $users = $repository->findAll();

        self::assertCount(5, $users);
    }
}
