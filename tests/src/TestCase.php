<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;
use WayOfDev\Cycle\Bridge\Laravel\Providers\CycleServiceProvider;
use WayOfDev\Cycle\Testing\Concerns\InteractsWithDatabase;
use WayOfDev\Cycle\Testing\RefreshDatabase;
use WayOfDev\RQL\Bridge\Laravel\Providers\RQLServiceProvider;

use function array_merge;

abstract class TestCase extends Orchestra
{
    // https://github.com/GrahamCampbell/Laravel-TestBench

    use InteractsWithDatabase;
    use RefreshDatabase;

    protected ?string $migrationsPath = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->migrationsPath = __DIR__ . '/../app/database/migrations/cycle';
        $this->cleanupMigrations($this->migrationsPath . '/*.php');
        $this->refreshDatabase();

        if (app()->environment() === 'testing') {
            config()->set([
                'cycle.tokenizer.directories' => array_merge(
                    config('cycle.tokenizer.directories'),
                    [__DIR__ . '/../app/Entities']
                ),
                'cycle.migrations.directory' => $this->migrationsPath,
            ]);
        }
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @param Application $app
     */
    protected function getPackageProviders($app): array
    {
        return [
            CycleServiceProvider::class,
            RQLServiceProvider::class,
        ];
    }
}
