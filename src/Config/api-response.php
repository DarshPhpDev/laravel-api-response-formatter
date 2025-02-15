<?php

return [
	
    /*
    |--------------------------------------------------------------------------
    | Customize keys of the returned response
    |--------------------------------------------------------------------------
    | For example change the status key to meta in the reponse.
    */

    'keys' => [
        'status' 			=> 'status',
        'code' 				=> 'code',
        'message' 			=> 'message',
        'error' 			=> 'error',
        'validation_errors' => 'validation_errors',
        'data' 				=> 'data',
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