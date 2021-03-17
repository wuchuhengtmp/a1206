<?php

declare(strict_types=1);
/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */

use \Simps\Server\Protocol\MQTT;

return [
    'mode' => SWOOLE_PROCESS,
    'ws' => [
        'ip' => '0.0.0.0',
        'port' => 9602,
        'sock_type' => SWOOLE_SOCK_TCP,
        'callbacks' => [
            "open" => [\App\Events\WebSocket::class, 'onOpen'],
            "message" => [\App\Events\WebSocket::class, 'onMessage'],
            "close" => [\App\Events\WebSocket::class, 'onClose'],
        ],
        'settings' => [
            'worker_num' => swoole_cpu_num(),
            'open_websocket_protocol' => true,
        ],
    ],
    'mqtt' => [
        'ip' => '0.0.0.0',
        'port' => (int) env('MQTT_PORT'),
        'callbacks' => [
            'close' => [\App\Events\MqttServer::class, 'onClose'],
        ],
        'receiveCallbacks' => [
            MQTT::CONNECT => [\App\Events\MqttServer::class, 'onMqConnect'],
            MQTT::PINGREQ => [\App\Events\MqttServer::class, 'onMqPingreq'],
            MQTT::DISCONNECT => [\App\Events\MqttServer::class, 'onMqDisconnect'],
            MQTT::PUBLISH => [\App\Events\MqttServer::class, 'onMqPublish'],
            MQTT::SUBSCRIBE => [\App\Events\MqttServer::class, 'onMqSubscribe'],
            MQTT::UNSUBSCRIBE => [\App\Events\MqttServer::class, 'onMqUnsubscribe'],
        ],
        'settings' => [
            'worker_num' => 1,
            'open_mqtt_protocol' => true,
        ],
    ],
];
