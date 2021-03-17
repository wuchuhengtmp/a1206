<?php

/**
 *  订阅器配置
 *
 * @author wuchuheng <wuchyuheng@163.com>
 */

use App\Listens\WebsocketListeners\LogSubscript as WsLogSubscript;

return [
    \App\Listens\MqttListens\LoginSubscript::class,
    \App\Listens\MqttListens\DisconnectSubscript::class,
    \App\Listens\MqttListens\LoggedSubscript::class,
    \App\Listens\MqttListens\RegisterSubscript::class,
    \App\Listens\MqttListens\LogSubscript::class,
    \App\Listens\MqttListens\SubscriptSubscript::class,
    \App\Listens\MqttListens\ReportSubscript::class,

    // websocket subscript
    \App\Listens\WebsocketListeners\LoginSubscript::class,
    \App\Listens\WebsocketListeners\RegisterSubscript::class,
    WsLogSubscript::class,
    \App\Listens\WebsocketListeners\PingSubscript::class
];
