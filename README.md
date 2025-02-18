
<p align="center"><img src="/art/socialcard.png" alt="Laravel Api Response Formatter"></p>

# Laravel API Response Formatter

[![Latest Version on Packagist](https://img.shields.io/packagist/v/darshphpdev/laravel-api-response-formatter.svg)](https://packagist.org/packages/darshphpdev/laravel-api-response-formatter)
[![Total Downloads](https://img.shields.io/packagist/dt/darshphpdev/laravel-api-response-formatter.svg)](https://packagist.org/packages/darshphpdev/laravel-api-response-formatter)
[![License](https://img.shields.io/packagist/l/darshphpdev/laravel-api-response-formatter.svg)](https://packagist.org/packages/darshphpdev/laravel-api-response-formatter)

A powerful Laravel package that standardizes your API responses with a clean, expressive syntax. Perfect for building consistent RESTful APIs.

## Features

- 🚀 **Simple & Expressive API** - Fluent interface for building responses
- 🎯 **Consistent Format** - Standardized response structure across your API
- 📦 **Built-in Support** for:
  - Success/Error responses
  - Validation errors
  - Pagination
  - Custom headers
  - Exception handling
- ⚙️ **Highly Configurable** - Customize keys, messages, and more
- 🔒 **Type-Safe** - Full TypeScript-like safety with PHP 7.4+
- 🧪 **Well Tested** - Comprehensive test coverage

## Quick Start

### Installation

```bash
composer require darshphpdev/laravel-api-response-formatter
```

### Basic Usage

```php
// Success Response
return api_response()
    ->success()
    ->message('Welcome!')
    ->send(['name' => 'Laravel']);

// Error Response
return api_response()
    ->error()
    ->code(404)
    ->message('Resource Not Found')
    ->send();

// With Validation Errors
return api_response()
    ->error()
    ->code(422)
    ->message('Validation Error')
    ->validationErrors($errors)
    ->send();

// With Pagination
return api_response()
    ->success()
    ->send(User::paginate(10));
```

### Response Format

```json
{
    "status": {
        "code": 200,
        "message": "Welcome!",
        "error": false,
        "validation_errors": []
    },
    "data": {
        "name": "Laravel"
    }
}
```

## Documentation

### Configuration

Publish the config file:
```bash
php artisan vendor:publish --tag=api-response-config
```

Customize response keys, messages, and more in `config/api-response.php`:

```php
return [
    'keys' => [
        'status' => 'status',
        'code' => 'code',
        // ... customize your keys
    ],
    'logging' => [
        'enabled' => env('API_RESPONSE_LOGGING', false),
    ],
    'error_messages' => [
        200 => 'Success',
        404 => 'Resource Not Found',
        // ... customize your messages
    ],
];
```

### Exception Handling

Add the trait to your controllers:

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
                ->send($user);
        } catch (\Throwable $e) {
            return $this->handleApiException($e);
        }
    }
}
```

## Advanced Usage

### Method Chaining

```php
return api_response()
    ->success()
    ->message('Created successfully')
    ->code(201)
    ->headers(['X-Custom-Header' => 'Value'])
    ->send($data);
```

### Pagination Support

```php
$users = User::paginate(10);

return api_response()
    ->success()
    ->message('Users retrieved')
    ->send($users);
```

Response includes pagination metadata:
```json
{
    "status": { ... },
    "data": {
        "items": [...],
        "pagination": {
            "total": 100,
            "per_page": 10,
            "current_page": 1,
            "last_page": 10
        }
    }
}
```

## Testing

```bash
composer test
```

## Compatibility

- PHP 7.4+
- Laravel 5.x and above
- PHPUnit 9.x for testing

## Contributing

Contributions are welcome!

## Security

If you discover any security-related issues, please email [mustafa.softcode@gmail.com] instead of using the issue tracker.

## Credits

- [MUSTAFA AHMED](https://github.com/DarshPhpDev)
- [All Contributors](../../contributors)

## License

This package is open-source software licensed under the  [MIT License](https://opensource.org/licenses/MIT).