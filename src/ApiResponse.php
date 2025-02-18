<?php

namespace DarshPhpDev\LaravelApiResponseFormatter;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\AbstractPaginator;

class ApiResponse
{
    // Method chaining properties
    private mixed $data = null;
    private int $code = 200;
    private ?string $message = null;
    private bool $error = false;
    private array $validationErrors = [];
    private array $headers = [];

    // Configuration properties
    private array $config;
    private array $keys;

    /**
     * Constructor.
     */
    public function __construct()
    {
        // Load the configurations
        $this->config = config('api-response', []);
        $this->keys = $this->config['keys'] ?? [];
    }

    /**
     * Get the error message for a given status code.
     *
     * @param int $code
     * @return string
     */
    private function getErrorMessage(int $code): string
    {
        return $this->config['error_messages'][$code] ?? 'Unknown Error';
    }

    /**
     * Set the response data.
     *
     * @param mixed $data
     * @return $this
     */
    public function data(mixed $data = null): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Set the response code.
     *
     * @param int $code
     * @return $this
     */
    public function code(int $code = 200): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Set the response message.
     *
     * @param string $message
     * @return $this
     */
    public function message(?string $message = null): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Mark the response as an error.
     *
     * @return $this
     */
    public function error(): self
    {
        $this->error = true;
        $this->code = $this->code === 200 ? 500 : $this->code;
        return $this;
    }

    /**
     * Mark the response as a success.
     *
     * @return $this
     */
    public function success(): self
    {
        $this->error = false;
        return $this;
    }

    /**
     * Set validation errors.
     *
     * @param array $validationErrors
     * @return $this
     */
    public function validationErrors(array $validationErrors = []): self
    {
        $this->validationErrors = $validationErrors;
        return $this;
    }

    /**
     * Set custom headers.
     *
     * @param array $headers
     * @return $this
     */
    public function headers(array $headers = []): self
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Send the response.
     * @param mixed $data
     * @return JsonResponse
     */
    public function send(mixed $data = null): JsonResponse
    {
        if (func_num_args() > 0) {
            $this->data = $data;
        }

        $this->message ??= $this->getErrorMessage($this->code);

        // Format paginated data
        if ($this->data instanceof AbstractPaginator) {
            $this->data = $this->formatPaginatedData($this->data);
        }

        // Build the response structure
        $response = [
            $this->keys['status'] => [
                $this->keys['code'] => $this->code,
                $this->keys['message'] => $this->message,
                $this->keys['error'] => $this->error,
                $this->keys['validation_errors'] => $this->validationErrors,
            ],
            $this->keys['data'] => $this->data,
        ];

        // Log the response if enabled
        if ($this->config['logging']['enabled'] ?? false) {
            logger()->info('API Response:', $response);
        }

        return response()->json(
            $response, 
            $this->code, 
            $this->headers, 
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * Format paginated data.
     *
     * @param AbstractPaginator $paginator
     * @return array
     */
    private function formatPaginatedData(AbstractPaginator $paginator): array
    {
        return [
            'items' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
            ],
        ];
    }
}