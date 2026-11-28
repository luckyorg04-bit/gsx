<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | Laravel 12 uses the "default" mailer and named transport definitions.
    | MAIL_DRIVER remains a fallback for older GSX environment files.
    |
    */
    'default' => env('MAIL_MAILER', env('MAIL_DRIVER', 'smtp')),

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'scheme' => env('MAIL_SCHEME', env('MAIL_ENCRYPTION') === 'ssl' ? 'smtps' : null),
            'url' => env('MAIL_URL'),
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 587),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env(
                'MAIL_EHLO_DOMAIN',
                parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST)
            ),
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => ['smtp', 'log'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global From Address
    |--------------------------------------------------------------------------
    */
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'GSX')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown Mail Settings
    |--------------------------------------------------------------------------
    */
    'markdown' => [
        'theme' => 'default',
        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Backward-compatible legacy configuration
    |--------------------------------------------------------------------------
    |
    | Keep these aliases while older GSX integrations are migrated to named
    | mailers. Laravel 12 resolves transports through the mailers array above.
    |
    */
    'driver' => env('MAIL_DRIVER', env('MAIL_MAILER', 'smtp')),
    'host' => env('MAIL_HOST', '127.0.0.1'),
    'port' => env('MAIL_PORT', 587),
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username' => env('MAIL_USERNAME'),
    'password' => env('MAIL_PASSWORD'),
    'sendmail' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs'),
];
