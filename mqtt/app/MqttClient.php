<?php
/**
 * Class MqttClient
 * @package App
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App;

class MqttClient
{
    static private $_instance = null;

    public function getInstance()
    {
        $config = [
            'host' => env('WS_MQTT_Client_HOST'),
            'port' => (int) env('WS_MQTT_Client_PORT'),
            'time_out' => (int) env('WS_MQTT_Client_TIME_OUT'),
            'username' => env('WS_MQTT_Client_USERNAME'),
            'password' => env('WS_MQTT_Client_PASSWORD'),
            'client_id' => env('WS_MQTT_Client_CLIENT_ID'),
            'keepalive' => env('WS_MQTT_Client_KEEPALIVE'),
        ];
        return new \Simps\Client\MQTTClient($config);
    }
}