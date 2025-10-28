<?php
declare(strict_types=1);

return [
    'app' => [
        'name' => 'SMS Onay Platformu',
        'base_url' => 'https://example.com',
        'force_https' => true,
        'default_locale' => 'tr',
        'fallback_locale' => 'en',
        'timezone' => 'Europe/Istanbul',
        'theme' => [
            'primary_color' => '#4f46e5',
            'secondary_color' => '#0ea5e9',
            'logo_path' => '/public/assets/img/logo.svg',
        ],
    ],
    'database' => [
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'sms_onay',
        'username' => 'sms_onay',
        'password' => 'secret',
        'charset' => 'utf8mb4',
    ],
    'security' => [
        'app_key' => 'change-me-please',
        'csrf_lifetime' => 900,
        'session_name' => 'sms_onay_session',
        'rate_limit' => [
            'window' => 60,
            'max_attempts' => 20,
        ],
        'hsts_max_age' => 15552000,
    ],
    'mail' => [
        'driver' => 'mail',
        'from' => 'no-reply@example.com',
        'from_name' => 'SMS Onay',
        'smtp' => [
            'host' => 'localhost',
            'port' => 587,
            'username' => '',
            'password' => '',
            'encryption' => 'tls',
        ],
    ],
    'payments' => [
        'shopier' => [
            'api_key' => '',
            'api_secret' => '',
            'enabled' => false,
        ],
        'iyzico' => [
            'api_key' => '',
            'api_secret' => '',
            'enabled' => false,
        ],
        'paytr' => [
            'merchant_id' => '',
            'merchant_key' => '',
            'merchant_salt' => '',
            'enabled' => false,
        ],
    ],
    'logging' => [
        'path' => __DIR__ . '/../storage/logs/app.log',
    ],
    'queue' => [
        'path' => __DIR__ . '/../storage/cache/jobs',
    ],
];
