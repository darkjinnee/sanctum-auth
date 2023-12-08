<?php

namespace Darkjinnee\SanctumAuth;

use Illuminate\Support\ServiceProvider;

class SanctumAuthServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'darkjinnee');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'darkjinnee');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sanctum-auth.php', 'sanctum-auth');

        // Register the service the package provides.
        $this->app->singleton('sanctum-auth', function ($app) {
            return new SanctumAuth;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['sanctum-auth'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/sanctum-auth.php' => config_path('sanctum-auth.php'),
        ], 'sanctum-auth.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/darkjinnee'),
        ], 'sanctum-auth.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/darkjinnee'),
        ], 'sanctum-auth.assets');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/darkjinnee'),
        ], 'sanctum-auth.lang');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
