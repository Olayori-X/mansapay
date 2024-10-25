<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */


        'paths' => ['api/*', 'sanctum/csrf-cookie'],  // Paths to apply CORS settings
        'allowed_methods' => ['*'],  // Allow all HTTP methods (GET, POST, PUT, DELETE, etc.)
        'allowed_origins' => ['*'],  // Allow requests from all origins, or specify URLs, e.g., ['https://example.com']
        'allowed_origins_patterns' => [],  // Wildcard pattern for origins, e.g., ['*.example.com']
        'allowed_headers' => ['*'],  // Allow all headers, or specify necessary headers
        'exposed_headers' => [],  // Headers to be exposed to the browser
        'max_age' => 0,  // Cache duration for preflight requests, in seconds
        'supports_credentials' => true,  // Set to true if you need to send cookies or auth tokens
    ];
    
