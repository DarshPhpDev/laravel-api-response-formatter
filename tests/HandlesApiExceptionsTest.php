<?php

namespace DarshPhpDev\LaravelApiResponseFormatter\Tests;

use DarshPhpDev\LaravelApiResponseFormatter\Traits\HandlesApiExceptions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Orchestra\Testbench\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Validator;

class HandlesApiExceptionsTest extends TestCase
{
    private array $defaultConfig = [
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
    ];

    // Test class using the trait
    private $testClass;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create anonymous class with the trait
        $this->testClass = new class {
            use HandlesApiExceptions;

            public function handle($e)
            {
                return $this->handleApiException($e);
            }
        };

        $this->app['config']->set('api-response', $this->defaultConfig);
    }

    protected function getPackageProviders($app): array
    {
        return [
            \DarshPhpDev\LaravelApiResponseFormatter\ApiResponseServiceProvider::class,
        ];
    }

    /** @test */
    public function it_handles_validation_exception(): void
    {
        $validator = Validator::make(
            ['email' => 'invalid-email'],
            ['email' => 'email']
        );

        $exception = new ValidationException($validator);

        $response = $this->testClass->handle($exception);
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('Validation Error', $content['status']['message']);
        $this->assertTrue($content['status']['error']);
        $this->assertArrayHasKey('email', $content['status']['validation_errors']);
    }

    /** @test */
    public function it_handles_model_not_found_exception(): void
    {
        $exception = new ModelNotFoundException();

        $response = $this->testClass->handle($exception);
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Resource Not Found', $content['status']['message']);
        $this->assertTrue($content['status']['error']);
    }

    /** @test */
    public function it_handles_http_exception(): void
    {
        $exception = new HttpException(403, 'Forbidden Access');

        $response = $this->testClass->handle($exception);
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('Forbidden Access', $content['status']['message']);
        $this->assertTrue($content['status']['error']);
    }

    /** @test */
    public function it_handles_generic_exception(): void
    {
        $exception = new \Exception('Unknown error');

        $response = $this->testClass->handle($exception);
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('Something went wrong', $content['status']['message']);
        $this->assertTrue($content['status']['error']);
    }
} 