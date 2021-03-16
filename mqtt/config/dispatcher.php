<?php

/**
 *  订阅器配置
 *
 * @author wuchuheng <wuchyuheng@163.com>
 */

return [
    \App\Listens\MqttListens\LoginSubscript::class,
    \App\Listens\MqttListens\DisconnectSubscript::class,
    \App\Listens\MqttListens\LoggedSubscript::class,
    \App\Listens\MqttListens\RegisterSubscript::class,
    \App\Listens\MqttListens\LogSubscript::class,
    \App\Listens\MqttListens\SubscriptSubscript::class,

    // websocket subscript
    \App\Listens\WebsocketListeners\LoginSubscript::class,
    \App\Listens\WebsocketListeners\RegisterSubscript::class,
];
