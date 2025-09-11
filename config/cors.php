<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],  // Ensure Sanctum cookie path is included

    'allowed_methods' => ['*'],  // Allows all HTTP methods

    'allowed_origins' => ['http://erp.atithibhavan.com'],  // Specify allowed frontend domain

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],  // Allows all headers

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,  // Set to true for cookies/session authentication
];
