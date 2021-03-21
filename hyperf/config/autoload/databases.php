<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'default' => [
        'driver' => env('HYPERF_DB_DRIVER', 'mysql'),
        'host' => env('HYPERF_DB_HOST', 'localhost'),
        'database' => env('HYPERF_DB_DATABASE', 'hyperf'),
        'port' => env('HYPERF_DB_PORT', 3306),
        'username' => env('HYPERF_DB_USERNAME', 'root'),
        'password' => env('HYPERF_DB_PASSWORD', ''),
        'charset' => env('HYPERF_DB_CHARSET', 'utf8'),
        'collation' => env('HYPERF_DB_COLLATION', 'utf8_unicode_ci'),
        'prefix' => env('HYPERF_DB_PREFIX', ''),
        'pool' => [
            'min_connections' => 1,
            'max_connections' => 10,
            'connect_timeout' => 10.0,
            'wait_timeout' => 3.0,
            'heartbeat' => -1,
            'max_idle_time' => (float) env('HYPERF_DB_MAX_IDLE_TIME', 60),
        ],
        'commands' => [
            'gen:model' => [
                'path' => 'app/Model',
                'force_casts' => true,
                'inheritance' => 'Model',
            ],
        ],
    ],
];
