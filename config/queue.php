<?php

declare(strict_types=1);

return [
    'default' => env('QUEUE_DRIVER', 'file'),
    'connections' => [
        'file' => [
            'driver' => 'file',
            'path' => __DIR__ . '/../storage/queue',
        ],
    ],
];
