<?php

namespace DarshPhpDev\LaravelApiResponseFormatter\Tests;

use Orchestra\Testbench\TestCase;

class HelpersTest extends TestCase
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

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            \DarshPhpDev\LaravelApiResponseFormatter\ApiResponseServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('api-response', $this->defaultConfig);
    }

    /** @test */
    public function it_creates_a_success_response_using_helper(): void
    {
        $expectedData = [
            'status' => [
                'code' => 200,
                'message' => 'Welcome!',
                'error' => false,
                'validation_errors' => [],
            ],
            'data' => ['name' => 'Laravel'],
        ];

        $response = api_response()
            ->success()
            ->message('Welcome!')
            ->data(['name' => 'Laravel'])
            ->send();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertJsonStringEqualsJsonString(
            json_encode($expectedData),
            $response->getContent()
        );
    }

    // Add other test methods here...
}