<?php

return [
    'driver' => env('SMS_DRIVER', 'log'),
    'from' => env('SMS_FROM', 'SPESSE'),

    'termii' => [
        'api_key' => env('TERMII_API_KEY'),
        'sender_id' => env('TERMII_SENDER_ID', env('SMS_FROM', 'SPESSE')),
        'channel' => env('TERMII_CHANNEL', 'generic'),
        'base_url' => env('TERMII_BASE_URL', 'https://api.ng.termii.com/api'),
    ],
];
