<?php

namespace Roofr\Weather;

use Illuminate\Support\ServiceProvider;

class WeatherServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Tells Laravel where to look for views when we use laravel-weather::folder.view
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-weather');

        // If we are booting from the console (IE. Artisan)
        if ($this->app->runningInConsole()) {
            // Allow vendor:publish of config by selecting the package config file, and the path to the apps config
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('weather.php'),
            ], 'config');
            // Allow vendor:publish of views by selecting the package view path, and the path to the deploy to
            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/laravel-weather'),
            ], 'views');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Merge the default config from this package with this packages config (laravel-weather::config)
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'weather');

        // Define a singleton for the weather app (ie. app('weather'))
        $this->app->singleton('weather', function ($app) {
            $config = $app->config->get('weather', array());

            return new WeatherClass($app->cache, $app->view, $config);
        });

    }
}
