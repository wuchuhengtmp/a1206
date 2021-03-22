<?php
/**
 * Class MqttClient
 * @package Utils
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace Utils;

use Simps\MQTT\Client;
use Simps\MQTT\Config\ClientConfig;

class MqttClient
{
    const SUCCESS_CONTENT = ['retcode' => 0];

    static private $_instance = null;

    public function getClient()
    {
            $ip = env('HPERF_MQTT_ADDRES');
            $port = (int) env('HPERF_MQTT_PORT');
            $config = [
                'userName' => env('HPERF_MQTT_USERNAME'), // 用户名
                'password' => env('HPERF_MQTT_PASSWORD'), // 密码
                'clientId' => env('HPERF_MQTT_CLIENTID') . '_' . time(), // 客户端id
                'keepAlive' => 10, // 默认0秒，设置成0代表禁用
                'protocolName' => 'MQTT', // 协议名，默认为MQTT(3.1.1版本)，也可为MQIsdp(3.1版本)
                'protocolLevel' => 4, // 协议等级，MQTT3.1.1版本为4，5.0版本为5，MQIsdp为3
                'properties' => [], // MQTT5 中所需要的属性
                'delay' => 3000, // 重连时的延迟时间 (毫秒)
                'maxAttempts' => 5, // 最大重连次数。默认-1，表示不限制
                'swooleConfig' => []
            ];
            $configObj = new \Simps\MQTT\Config\ClientConfig($config);
            $client = (new Client($ip, $port, $configObj));
            while (! $client->connect(true)) {
                \Swoole\Coroutine::sleep(3);
                $client->connect(true);
            }
            self::$_instance = $client;
        return self::$_instance;
    }
}