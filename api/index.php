<?php

// Serverless entry point for Vercel deployment
// This file bootstraps Laravel and handles incoming requests

require __DIR__ . '/../vendor/autoload.php';

// Create necessary directories in /tmp for serverless environment
$directories = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/sessions',
    '/tmp/framework/views',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->handleRequest(
    \Illuminate\Http\Request::capture()
);
