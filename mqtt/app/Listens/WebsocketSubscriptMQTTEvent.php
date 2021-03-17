<?php
/**
 * websocket 服务的mqtt客户端订阅mqtt服务的事件消息
 * @package App\Listens
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens;

use Simps\Client\MQTTClient;
use Simps\Singleton;
use function Swoole\Coroutine\run;

class WebsocketSubscriptMQTTEvent
{
    use Singleton;

    /**
     * g启动ws服务并创建mqtt 客户端
     * @param $server
     */
    public function handle($server): void
    {
        $wsOpenFlag = 'open_websocket_protocol';
        if (array_key_exists($wsOpenFlag, $server->setting) && $server->setting[$wsOpenFlag]) {
            $config = [
                'host' => env('WS_MQTT_Client_HOST'),
                'port' => (int) env('WS_MQTT_Client_PORT'),
                'time_out' => (int) env('WS_MQTT_Client_TIME_OUT'),
                'username' => env('WS_MQTT_Client_USERNAME'),
                'password' => env('WS_MQTT_Client_PASSWORD'),
                'client_id' => env('WS_MQTT_Client_CLIENT_ID'),
                'keepalive' => env('WS_MQTT_Client_KEEPALIVE'),
            ];
            go(function () use ($config) {
//                $this->_client($config);
            });
        }
    }

    private function _client(array $config): void
    {
        $client = new MQTTClient($config);
        $will = [
            'topic' => 'simpsmqtt/username/update',
            'qos' => 1,
            'retain' => 0,
            'content' => "123",
        ];
        while (! $client->connect(true, $will)) {
            \Swoole\Coroutine::sleep(3);
            $client->connect(true, $will);
        }
        $topics['mqtt_event'] = 1;
        $timeSincePing = time();
        $client->subscribe($topics);
        while (true) {
            $buffer = $client->recv();
            var_dump($buffer);
            if ($buffer && $buffer !== true) {
                $timeSincePing = time();
            }
            if (isset($config['keepalive']) && $timeSincePing < (time() - $config['keepalive'])) {
                $buffer = $client->ping();
                if ($buffer) {
                    echo '发送心跳包成功' . PHP_EOL;
                    $timeSincePing = time();
                } else {
                    $client->close();
                    break;
                }
            }
        }
    }
}