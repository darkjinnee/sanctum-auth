<?php

namespace Darkjinnee\SanctumAuth;

use Darkjinnee\SanctumAuth\Console\Commands\AttachAbility;
use Darkjinnee\SanctumAuth\Console\Commands\AttachAbilityGroup;
use Darkjinnee\SanctumAuth\Console\Commands\AttachedAbility;
use Darkjinnee\SanctumAuth\Console\Commands\AttachedAbilityGroup;
use Darkjinnee\SanctumAuth\Console\Commands\CreateAbility;
use Darkjinnee\SanctumAuth\Console\Commands\CreateAbilityGroup;
use Darkjinnee\SanctumAuth\Console\Commands\DeleteAbility;
use Darkjinnee\SanctumAuth\Console\Commands\DeleteAbilityGroup;
use Darkjinnee\SanctumAuth\Console\Commands\DetachAbility;
use Darkjinnee\SanctumAuth\Console\Commands\DetachAbilityGroup;
use Darkjinnee\SanctumAuth\Console\Commands\ListAbility;
use Darkjinnee\SanctumAuth\Console\Commands\ListAbilityGroup;
use Darkjinnee\SanctumAuth\Models\PersonalAccessToken;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class SanctumAuthServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app['db.schema']->morphUsingUuids();
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'darkjinnee');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'darkjinnee');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
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
     * @return array<string>
     */
    public function provides(): array
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

        $this->commands([
            ListAbility::class,
            ListAbilityGroup::class,
            CreateAbility::class,
            CreateAbilityGroup::class,
            DeleteAbility::class,
            DeleteAbilityGroup::class,
            AttachedAbility::class,
            AttachedAbilityGroup::class,
            AttachAbility::class,
            AttachAbilityGroup::class,
            DetachAbility::class,
            DetachAbilityGroup::class,
        ]);

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
