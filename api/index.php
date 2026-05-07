<?php

// Serverless entry point for Vercel deployment
// This file bootstraps Laravel and handles incoming requests

// Create necessary directories in /tmp for serverless environment
$directories = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/sessions',
    '/tmp/framework/views',
    '/tmp/framework/cache',
    '/tmp/framework/sessions',
    '/tmp/logs',
    '/tmp/storage/logs',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Fix SCRIPT_NAME so Laravel can correctly resolve the request URI.
// Vercel sets SCRIPT_NAME to "/api/index.php" which causes Laravel to
// strip the "/api" prefix from the URI, resulting in 404s for API routes.
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

// Forward to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
