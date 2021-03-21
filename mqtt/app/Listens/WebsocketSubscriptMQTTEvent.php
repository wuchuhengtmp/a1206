<?php
/**
 * websocket 服务的mqtt客户端订阅mqtt服务的事件消息
 * @package App\Listens
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens;

use App\Events\WebsocketEvents\MqttClientPingEvent;
use App\GlobalChannel;
use FastRoute\Dispatcher;
use Simps\Client\MQTTClient;
use Simps\Singleton;
use Swoole\Coroutine\Redis;

class WebsocketSubscriptMQTTEvent
{
    use Singleton;

    /**
     * 启动ws服务并创建mqtt 客户端
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
                $this->_client($config);
            });
        }
    }

    private function _client(array $config): void
    {
        $client = new MQTTClient($config);
        while (! $client->connect(true)) {
            \Swoole\Coroutine::sleep(3);
            $client->connect(true);
        }
        $topics['mqtt_event'] = 1;
        $timeSincePing = time();
        $client->subscribe($topics);
        $this->_send($config, $client);
        while (true) {
            $buffer = $client->recv();
            if ($buffer && $buffer !== true) {
                $timeSincePing = time();
            }
            if (isset($config['keepalive']) && $timeSincePing < (time() - $config['keepalive'])) {
                $buffer = $client->ping();
                if ($buffer) {
//                    var_dump($buffer);
//                    \App\Dispatcher::getInstance()->dispatch(new MqttClientPingEvent(0), MqttClientPingEvent::NAME);
                    $timeSincePing = time();
                } else {
                    $client->close();
                    break;
                }
            }
        }
    }

    private function _send($config, MQTTClient $client)
    {
        go(function() use($config, $client) {
            $redis = new Redis();
            $redis->connect(env('REDIS_HOST', '127.0.0.1'), (int) env('REDIS_HOST_PORT', 6379));
            if ($redis->subscribe(['dataForWsMqttClient'])) {
                while ($msg = $redis->recv()) {
                    list($type, $name, $info) = $msg;
                    var_dump($msg);
                    switch ($type) {
                        case 'subscript':
                            break;
                        case 'message':
                                $info = json_decode($info, true);
                                $topic = $info['topic'];
                                $qos = $info['qos'];
                                $retain = $info['retain'];
                                $content = $info['content'];
                                $client->publish($topic, $content);
                            break;
                    }
                }
            }
        });
    }
}