<?php

return [
	
	/*
	*	Customize keys of the returned response.
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
    *	Enable logging if you want to log all api responses.
    *	Default is false.
    */
    'logging' => [
        'enabled' => env('API_RESPONSE_LOGGING', false),
    ],
	
	/*
	*	Here you can customize your default status codes and their corresponding messages.
	*/
    'error_messages' => [
        200 => 'Success',
        400 => 'Bad Request',
        401 => 'Unauthorized Access',
        403 => 'Forbidden',
        404 => 'Resource Not Found',
        500 => 'Internal Server Error',
    ],
];