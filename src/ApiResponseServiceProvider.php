<?php

namespace DarshPhpDev\LaravelApiResponseFormatter;

use Illuminate\Support\ServiceProvider;

class ApiResponseServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge package configuration with application configuration
        $this->mergeConfigFrom(__DIR__ . '/Config/api-response.php', 'api-response');
    }

    public function boot()
    {
        // Publish configuration file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/Config/api-response.php' => config_path('api-response.php'),
            ], 'api-response-config');
        }

        // Load helper file
        require_once __DIR__ . '/helpers.php';
    }
}