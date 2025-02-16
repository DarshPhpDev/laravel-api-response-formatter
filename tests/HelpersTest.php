<?php

namespace DarshPhpDev\LaravelApiResponseFormatter\Tests;

use Orchestra\Testbench\TestCase;

class HelpersTest extends TestCase
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
    public function it_creates_a_success_response_using_helper()
    {
        $response = api_response()
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

    // Add other test methods here...
}