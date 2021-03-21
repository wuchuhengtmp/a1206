<?php

/**
 *  订阅器配置
 *
 * @author wuchuheng <wuchyuheng@163.com>
 */

//use App\Listens\WebsocketListeners\LogSubscript as WsLogSubscript;

return [
//    \App\Listens\MqttListens\LoginSubscript::class,
//    \App\Listens\MqttListens\DisconnectSubscript::class,
//    \App\Listens\MqttListens\LoggedSubscript::class,
//    \App\Listens\MqttListens\RegisterSubscript::class,
//    \App\Listens\MqttListens\LogSubscript::class,
//    \App\Listens\MqttListens\SubscriptSubscript::class,
//    \App\Listens\MqttListens\ReportSubscript::class,
//    \App\Listens\MqttListens\UpdataFileSubscript::class,
//    \App\Listens\MqttListens\UpdataFileResponseSubscript::class,
//
//    // websocket subscript
    \App\Listener\WebsocketListeners\LoginSubscript::class,
//    \App\Listens\WebsocketListeners\RegisterSubscript::class,
//    WsLogSubscript::class,
//    \App\Listens\WebsocketListeners\PingSubscript::class,
//    \App\Listens\WebsocketListeners\ShowCategoriesSubscript::class,
//    \App\Listens\WebsocketListeners\ShowMyDevicesSubscript::class,
//    \App\Listens\WebsocketListeners\ShowDeviceDetailSubscript::class,
//    \App\Listens\WebsocketListeners\UploadDeviceFileSubscript::class,
//    \App\Listens\WebsocketListeners\ShowDeviceFilesSubscript::class,
//    \App\Listens\WebsocketListeners\DestroyDeviceFileSubscript::class,
//    \App\Listens\WebsocketListeners\UploadDeviceFileSubscript::class,
//    \App\Listens\WebsocketListeners\UpdateDeviceFileSubscript::class,
];