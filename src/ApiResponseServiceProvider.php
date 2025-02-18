<?php

namespace DarshPhpDev\LaravelApiResponseFormatter;

use Illuminate\Support\ServiceProvider;

class ApiResponseServiceProvider extends ServiceProvider
{
    // Define path as a constant to avoid repetition and make maintenance easier
    private const CONFIG_PATH = __DIR__ . '/Config/api-response.php';
    
    public function register(): void
    {
        // Merge package configuration with application configuration
        $this->mergeConfigFrom(self::CONFIG_PATH, 'api-response');
    }

    public function boot(): void
    {
        // Publish configuration file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::CONFIG_PATH => config_path('api-response.php'),
            ], 'api-response-config');
        }

        // Load helper file
        require_once __DIR__ . '/helpers.php';
    }
}