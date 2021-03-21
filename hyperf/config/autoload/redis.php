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
        'host' => env('HYPERF_REDIS_HOST', 'localhost'),
        'auth' => env('HYPERF_REDIS_AUTH', null),
        'port' => (int) env('HYPERF_REDIS_PORT', 6379),
        'db' => (int) env('HYPERF_REDIS_DB', 0),
        'pool' => [
            'min_connections' => 1,
            'max_connections' => 10,
            'connect_timeout' => 10.0,
            'wait_timeout' => 3.0,
            'heartbeat' => -1,
            'max_idle_time' => (float) env('HYPERF_REDIS_MAX_IDLE_TIME', 60),
        ],
    ],
];
