<?php

declare(strict_types=1);  // Add strict types for better type safety

use DarshPhpDev\LaravelApiResponseFormatter\ApiResponse;

if (!function_exists('api_response')) {
    /**
     * Global helper function for API responses.
     *
     * @return ApiResponse A new instance of the ApiResponse class
     */
    function api_response(): ApiResponse
    {
        return app(ApiResponse::class);  // Use Laravel's service container instead of direct instantiation
    }
}