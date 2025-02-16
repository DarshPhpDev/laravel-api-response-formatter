
<p align="center"><img src="/art/socialcard.png" alt="Social Card of Laravel Api Response Formatter"></p>

# Laravel Api Response Formatter

A powerful and flexible Laravel package for standardizing API responses. This package provides a clean and expressive way to format API responses, including success, error, and paginated responses, with support for method chaining and customizable configurations.

---

## Table of Contents
1. [Installation](#installation)
2. [Usage](#usage)
   - [Basic Usage](#basic-usage)
   - [Method Chaining](#method-chaining)
   - [Pagination](#pagination)
   - [Validation Errors](#validation-errors)
   - [Custom Headers](#custom-headers)
3. [Configuration](#configuration)
4. [Exception Handling](#exception-handling)
5. [Testing](#testing)
6. [Compatibility](#compatibility)
7. [Contributing](#contributing)
8. [License](#license)

---

## Installation

Install the package via Composer:

```bash
composer require darshphpdev/laravel-api-response-formatter
```
----------

## Usage

### Basic Usage

The package provides a global helper function  `api_response()`  to format API responses.

```php
return api_response()
    ->success()
    ->message('Welcome!')
    ->data(['name' => 'Laravel'])
    ->send();
```
Or use the short version if you don't need to customize the message
```php
return api_response()
    ->success()
    ->send(['name' => 'Laravel']);
```
### Method Chaining

You can chain methods to build responses in a fluent and expressive way.

#### Success Response

```php
return api_response()
    ->success()
    ->message('Welcome!')
    ->data(['name' => 'Laravel'])
    ->send();
```
#### Error Response

```php
return api_response()
    ->error()
    ->code(404)
    ->message('Resource Not Found')
    ->send();
```
#### Pagination

Format paginated responses effortlessly.

```php
return api_response()
    ->data(User::paginate(10))
    ->send();
```
#### Validation Errors

Automatically format validation errors.

```php
return api_response()
    ->error()
    ->code(422)
    ->message('Validation Error')
    ->validationErrors($errors)
    ->send();
```
#### Custom Headers

Add custom headers to your response.

```php
return api_response()
    ->success()
    ->headers(['X-Custom-Header' => 'Value'])
    ->send(['name' => 'Laravel']);
```
----------

## Configuration

Publish the configuration file to customize the response structure and behavior:

```bash
php artisan vendor:publish --tag=api-response
```
This will create an  `api-response.php`  file in your  `config`  directory. Here’s an example of the configuration:

```php
return [
    
    /*
    |--------------------------------------------------------------------------
    | Customize keys of the returned response
    |--------------------------------------------------------------------------
    | For example change the status key to meta in the reponse.
    */

    'keys' => [
        'status'            => 'status',
        'code'              => 'code',
        'message'           => 'message',
        'error'             => 'error',
        'validation_errors' => 'validation_errors',
        'data'              => 'data',
    ],



    /*
    |--------------------------------------------------------------------------
    | Enable logging.
    |--------------------------------------------------------------------------
    | Set as true if you want to log all api responses, default is false
    */

    'logging' => [
        'enabled' => env('API_RESPONSE_LOGGING', false),
    ],
    


    /*
    |--------------------------------------------------------------------------
    | customize default status codes.
    |--------------------------------------------------------------------------
    | Here you can customize your default status codes and their corresponding messages
    */

   'error_messages' => [
        200 => 'Success',
        201 => 'Created',
        204 => 'No Content',
        400 => 'Bad Request',
        401 => 'Unauthorized Access',
        403 => 'Forbidden',
        404 => 'Resource Not Found',
        405 => 'Method Not Allowed',
        429 => 'Too Many Requests',
        500 => 'Internal Server Error',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
    ],
];
```
### Customizing Response Keys

You can change the keys used in the response structure. For example, to change  `status`  to  `meta`, update the configuration:

```php
'keys' => [
    'status'            => 'meta',
    'code'              => 'status_code',
    'message'           => 'message',
    'error'             => 'is_error',
    'validation_errors' => 'errors',
    'data'              => 'payload',
],
```
### Logging

Enable logging to log all API responses for debugging:

```php
'logging' => [
    'enabled' => true,
],
```
----------
### Error Messages

Enable logging to log all API responses for debugging:

```php
'error_messages' => [
    200 => 'Ok!',
],
```
----------

## Exception Handling

The package includes a trait to automatically format exceptions into consistent API responses.

### Using the Trait

Add the  `HandlesApiExceptions`  trait to your controller:

```php
use DarshPhpDev\LaravelApiResponseFormatter\Traits\HandlesApiExceptions;

class UserController extends Controller
{
    use HandlesApiExceptions;

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return api_response()
                ->success()
                ->data($user)
                ->send();
        } catch (\Throwable $e) {
            return $this->handleApiException($e);
        }
    }
}
```
### Example Responses

-   **Validation Exception**:
    
    ```json
    {
        "status": {
            "code": 422,
            "message": "Validation Error",
            "error": true,
            "validation_errors": {
                "email": ["The email field is required."]
            }
        },
        "data": null
    }
    ```
-   **Model Not Found Exception**:
    
    ```json
    {
        "status": {
            "code": 404,
            "message": "Resource Not Found",
            "error": true,
            "validation_errors": []
        },
        "data": null
    }
    ```

## Testing
To run the test suite, use the following command:
```bash
composer test
```

## Compatibility

This package is compatible with  **all Laravel versions starting from Laravel 5**. It does not depend on any Laravel or PHP version-specific features, making it highly versatile and easy to integrate into any Laravel project.

----------

## Contributing

Contributions are welcome! Please follow these steps:

1.  Fork the repository.
    
2.  Create a new branch (`git checkout -b feature/YourFeature`).
    
3.  Commit your changes (`git commit -m 'Add some awesome feature'`).
    
4.  Push to the branch (`git push origin feature/YourFeature`).
    
5.  Open a pull request.
    

----------

## License

This package is open-source software licensed under the  [MIT License](https://opensource.org/licenses/MIT).