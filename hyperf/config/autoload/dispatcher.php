<?php

/**
 *  订阅器配置
 *
 * @author wuchuheng <wuchyuheng@163.com>
 */

//use App\Listener\WebsocketListeners\LogSubscript as WsLogSubscript;

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
    \App\Listener\WebsocketListeners\RegisterSubscript::class,
//    WsLogSubscript::class,
    \App\Listener\WebsocketListeners\PingSubscript::class,
    \App\Listener\WebsocketListeners\ShowCategoriesSubscript::class,
    \App\Listener\WebsocketListeners\ShowMyDevicesSubscript::class,
    \App\Listener\WebsocketListeners\ShowDeviceDetailSubscript::class,
//    \App\Listener\WebsocketListeners\UploadDeviceFileSubscript::class,
    \App\Listener\WebsocketListeners\ShowDeviceFilesSubscript::class,
//    \App\Listener\WebsocketListeners\DestroyDeviceFileSubscript::class,
//    \App\Listener\WebsocketListeners\UploadDeviceFileSubscript::class,
    \App\Listener\WebsocketListeners\UpdateDeviceFileSubscript::class,
    \App\Listener\WebsocketListeners\DevicePlaySubscript::class,
    \App\Listener\WebsocketListeners\SetDevicesSoundSubscript::class,
    \App\Listener\WebsocketListeners\PlayFilesSubscript::class,
    \App\Listener\WebsocketListeners\PlayModeSubscript::class,
    \App\Listener\WebsocketListeners\AddConfigTimeSubscript::class,
    \App\Listener\WebsocketListeners\DisconnectSubscript::class
];
