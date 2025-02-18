<?php

namespace DarshPhpDev\LaravelApiResponseFormatter\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

trait HandlesApiExceptions
{
    /**
     * Maps exception types to their handlers
     */
    private array $exceptionHandlers = [];

    /**
     * Initialize exception handlers
     */
    private function initializeExceptionHandlers(): void
    {
        $this->exceptionHandlers = [
            ValidationException::class => fn(ValidationException $e): JsonResponse => 
                api_response()
                    ->error()
                    ->code(422)
                    ->message('Validation Error')
                    ->validationErrors($e->errors())
                    ->send(),

            ModelNotFoundException::class => fn(ModelNotFoundException $e): JsonResponse => 
                api_response()
                    ->error()
                    ->code(404)
                    ->message('Resource Not Found')
                    ->send(),

            HttpException::class => fn(HttpException $e): JsonResponse => 
                api_response()
                    ->error()
                    ->code($e->getStatusCode())
                    ->message($e->getMessage())
                    ->send(),
        ];
    }

    /**
     * Handle exceptions and return a formatted API response.
     */
    protected function handleApiException(Throwable $e): JsonResponse
    {
        if (empty($this->exceptionHandlers)) {
            $this->initializeExceptionHandlers();
        }

        // Find and execute the handler for the exception
        foreach ($this->exceptionHandlers as $exceptionType => $handler) {
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