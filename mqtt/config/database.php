<?php

declare(strict_types=1);
/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */
return [
    'host' => env('DB_HOST'),
    'port' => 3306,
    'database' => env('DB_NAME'),
    'username' => env('DB_USER'),
    'password' => env('DB_PASSWORD'),
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ],
    'size' => 64,
];
