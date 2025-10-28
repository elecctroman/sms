<?php

declare(strict_types=1);

return [
    'name' => 'SMS Verify Platform',
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'https://localhost'),
    'locale' => env('APP_LOCALE', 'tr'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'key' => env('APP_KEY'),
    'timezone' => 'Europe/Istanbul',
    'csrf_secret' => env('CSRF_SECRET'),
];
