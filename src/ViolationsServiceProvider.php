<?php

namespace Ajency\Violations; 

use Illuminate\Support\ServiceProvider;

class ViolationsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');

        $this->publishes([
            __DIR__.'/config' => config_path()
        ]);

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // merge package config to app's config
        $this->mergeConfigFrom(__DIR__ . '/config/aj-vio-config.php', 'aj-vio-config');
    }
}
