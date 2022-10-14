<?php

namespace IproSync;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'ipro-sync');

        if ($this->app->runningInConsole()) {
            if (IproSoftwareSync::$runsMigrations) {
                $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
            }
            $this->publishes([
                __DIR__.'/../config/iprosoftware-sync.php' => config_path('iprosoftware-sync.php'),
            ], 'config');


            $this->commands([
                \IproSync\Console\Commands\SettingsPullCommand::class,
                \IproSync\Console\Commands\ContactsPullCommand::class,
                \IproSync\Console\Commands\PropertiesPullCommand::class,
                \IproSync\Console\Commands\PropertiesCustomRatesPullCommand::class,
                \IproSync\Console\Commands\AvailabilityPullCommand::class,
                \IproSync\Console\Commands\BookingsPullCommand::class,
                \IproSync\Console\Commands\BlockoutsPullCommand::class,
                \IproSync\Console\Commands\IproPullDatabaseCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/iprosoftware-sync.php', 'iprosoftware-sync');
    }
}
