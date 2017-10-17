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
            __DIR__.'/config' => config_path(),
            __DIR__.'/models/Violation.php' => app_path('Violation.php'),
            __DIR__.'/models/ViolationType.php' => app_path('ViolationType.php'),
        ]);

        $this->commands(['Ajency\Violations\Commands\GenerateViolationEmailTemplates']);

        $violationsConfig = json_decode(config('aj-vio-config.create_violation_rules'));

        foreach($violationsConfig as $violation) {
            // for each violation type copy the default template
            $this->publishes([
                __DIR__.'/views/default_email.blade.php' => resource_path('views/violations/'.$violation->violation_type.'.blade.php')]);
        }
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
