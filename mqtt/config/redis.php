<?php

declare(strict_types=1);
/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

return [
    'host' => '127.0.0.1',
//    'port' => (int) $_ENV['REDIS_HOST_PORT'],
    'port' => 6379,
    'auth' => '',
    'db_index' => 0,
    'time_out' => 1,
    'size' => 64,
];
