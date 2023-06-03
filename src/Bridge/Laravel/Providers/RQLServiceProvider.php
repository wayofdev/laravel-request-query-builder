<?php

declare(strict_types=1);

namespace WayOfDev\RQL\Bridge\Laravel\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use WayOfDev\RQL\Config;
use WayOfDev\RQL\Contracts\ConfigRepository;
use WayOfDev\RQL\Contracts\ProcessorInterface;
use WayOfDev\RQL\RQLFactory;

final class RQLServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../../../config/rql.php' => config_path('rql.php'),
            ], 'config');
        }
    }

    /**
     * @throws BindingResolutionException
     */
    public function register(): void
    {
        // @phpstan-ignore-next-line
        if (! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../../../../config/rql.php', 'rql');
        }

        $this->registerConfig();
        $this->registerProcessor();
    }

    private function registerConfig(): void
    {
        $defaultProcessor = config('rql.default_processor');

        $this->app->singleton(ConfigRepository::class, static function () {
            return Config::fromArray(config('rql'));
        });
    }

    /**
     * @throws BindingResolutionException
     */
    private function registerProcessor(): void
    {
        $config = $this->app->make(ConfigRepository::class);

        $this->app->singleton(RQLFactory::class, static function () use ($config): RQLFactory {
            return new RQLFactory($config);
        });

        $this->app->bind(ProcessorInterface::class, function (Application $app) {
            /** @var RQLFactory $rql */
            $rql = $app->make(RQLFactory::class);

            return $rql->processor();
        });
    }
}
