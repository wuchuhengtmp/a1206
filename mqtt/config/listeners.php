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
    //Server::onStart
    'start' => [
        // 启动服务后便创建mqtt客户端
        [\App\Listens\WebsocketSubscriptMQTTEvent::class, 'handle']
    ],
    //Server::onWorkerStart
    'workerStart' => [
        [\App\Listens\Pool::class, 'workerStart'],
    ],
];
