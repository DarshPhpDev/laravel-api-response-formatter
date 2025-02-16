<?php

namespace DarshPhpDev\LaravelApiResponseFormatter\Tests;

use DarshPhpDev\LaravelApiResponseFormatter\ApiResponse;
use Orchestra\Testbench\TestCase;

class ApiResponseTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \DarshPhpDev\LaravelApiResponseFormatter\ApiResponseServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Load the package's configuration
        $app['config']->set('api-response', [
            'keys' => [
                'status' => 'status',
                'code' => 'code',
                'message' => 'message',
                'error' => 'error',
                'validation_errors' => 'validation_errors',
                'data' => 'data',
            ],
            'logging' => [
                'enabled' => false,
            ],
            'error_messages' => [
                200 => 'Success',
                400 => 'Bad Request',
                401 => 'Unauthorized Access',
                403 => 'Forbidden',
                404 => 'Resource Not Found',
                500 => 'Internal Server Error',
            ],
        ]);
    }

    /** @test */
    public function it_creates_a_success_response()
    {
        $response = (new ApiResponse())
            ->success()
            ->message('Welcome!')
            ->data(['name' => 'Laravel'])
            ->send();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'status' => [
                    'code' => 200,
                    'message' => 'Welcome!',
                    'error' => false,
                    'validation_errors' => [],
                ],
                'data' => ['name' => 'Laravel'],
            ]),
            $response->getContent()
        );
    }

    /** @test */
    public function it_creates_an_error_response()
    {
        $response = (new ApiResponse())
            ->error()
            ->code(404)
            ->message('Resource Not Found')
            ->send();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'status' => [
                    'code' => 404,
                    'message' => 'Resource Not Found',
                    'error' => true,
                    'validation_errors' => [],
                ],
                'data' => null,
            ]),
            $response->getContent()
        );
    }

    /** @test */
    public function it_creates_a_paginated_response()
    {
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            ['item1', 'item2'],
            2, // Total items
            10, // Items per page
            1 // Current page
        );

        $response = (new ApiResponse())
            ->data($paginator)
            ->send();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'status' => [
                    'code' => 200,
                    'message' => 'Success',
                    'error' => false,
                    'validation_errors' => [],
                ],
                'data' => [
                    'items' => ['item1', 'item2'],
                    'pagination' => [
                        'total' => 2,
                        'per_page' => 10,
                        'current_page' => 1,
                        'last_page' => 1,
                    ],
                ],
            ]),
            $response->getContent()
        );
    }

    /** @test */
    public function it_creates_a_response_with_validation_errors()
    {
        $response = (new ApiResponse())
            ->error()
            ->code(422)
            ->message('Validation Error')
            ->validationErrors(['email' => ['The email field is required.']])
            ->send();

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'status' => [
                    'code' => 422,
                    'message' => 'Validation Error',
                    'error' => true,
                    'validation_errors' => [
                        'email' => ['The email field is required.'],
                    ],
                ],
                'data' => null,
            ]),
            $response->getContent()
        );
    }

    /** @test */
    public function it_creates_a_response_with_custom_headers()
    {
        $response = (new ApiResponse())
            ->success()
            ->headers(['X-Custom-Header' => 'Value'])
            ->send(['name' => 'Laravel']);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->has('X-Custom-Header'));
        $this->assertEquals('Value', $response->headers->get('X-Custom-Header'));
    }
}