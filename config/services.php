<?php

declare(strict_types=1);

return [
    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'chat_id' => env('TELEGRAM_CHAT_ID'),
    ],
    'mail' => [
        'mailer' => env('MAIL_MAILER', 'smtp'),
        'host' => env('MAIL_HOST'),
        'port' => env('MAIL_PORT'),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'encryption' => env('MAIL_ENCRYPTION'),
        'from_address' => env('MAIL_FROM_ADDRESS'),
        'from_name' => env('MAIL_FROM_NAME'),
    ],
];
