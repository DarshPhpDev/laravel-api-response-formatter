<?php

use DarshPhpDev\LaravelApiResponseFormatter\ApiResponse;

if (!function_exists('api_response')) {
    /**
     * Global helper function for API responses.
     *
     * @return ApiResponse
     */
    function api_response(): ApiResponse
    {
        return new ApiResponse();
    }
}