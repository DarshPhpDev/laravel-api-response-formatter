<?php

namespace DarshPhpDev\LaravelApiResponseFormatter\Traits;

use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait HandlesApiExceptions
{
    /**
     * Handle exceptions and return a formatted API response.
     *
     * @param \Throwable $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleApiException(\Throwable $e): \Illuminate\Http\JsonResponse
    {
        $handlers = [
            ValidationException::class => function ($e) {
                return api_response()
                    ->error()
                    ->code(422)
                    ->message('Validation Error')
                    ->validationErrors($e->errors())
                    ->send();
            },
            ModelNotFoundException::class => function ($e) {
                return api_response()
                    ->error()
                    ->code(404)
                    ->message('Resource Not Found')
                    ->send();
            },
            HttpException::class => function ($e) {
                return api_response()
                    ->error()
                    ->code($e->getStatusCode())
                    ->message($e->getMessage())
                    ->send();
            },
        ];

        // Find the handler for the exception
        foreach ($handlers as $exceptionType => $handler) {
            if ($e instanceof $exceptionType) {
                return $handler($e);
            }
        }

        // Default handler for uncaught exceptions
        return api_response()
            ->error()
            ->code(500)
            ->message('Something went wrong')
            ->send();
    }
}