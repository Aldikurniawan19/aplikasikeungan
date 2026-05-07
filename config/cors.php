<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Settings untuk mengontrol akses Cross-Origin dari Flutter app.
    | Karena Flutter mobile app menggunakan HTTP client langsung,
    | kita izinkan semua origin.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', '*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
