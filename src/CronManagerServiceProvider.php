<?php

namespace Mugen\LaravelCronManager;

use Illuminate\Support\ServiceProvider;
use Mugen\LaravelCronManager\Commands\CronManagerCommand;
use Mugen\LaravelCronManager\Server\Manager;

class CronManagerServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
        $this->registerManager();
        $this->registerCommands();
    }

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/cron.php' => config_path('cron.php'),
        ], 'config');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'cron.manager',
        ];
    }

    /**
     * Merge Config
     */
    protected function mergeConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/cron.php', 'cron');
    }

    /**
     * Register Manager
     */
    protected function registerManager()
    {
        $this->app->singleton('cron.manager', function ($app) {
            return new Manager($app);
        });
    }

    /**
     * Register commands.
     */
    protected function registerCommands()
    {
        $this->commands([
            CronManagerCommand::class,
        ]);
    }
}
