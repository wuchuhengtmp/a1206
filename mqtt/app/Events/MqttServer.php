<?php


namespace App\Events;


use Simps\Server\Protocol\MqttInterface;
use \Simps\DB\BaseRedis;

class MqttServer implements MqttInterface
{

    public function onMqConnect($server, int $fd, $fromId, $data)
    {
        $redis = new BaseRedis();
        $res = $redis->set("ifcon", "asfasdfasdfsda");
        var_dump($res);
        // TODO: Implement onMqConnect() method.
    }

    public function onMqPingreq($server, int $fd, $fromId, $data): bool
    {
        // TODO: Implement onMqPingreq() method.
    }

    public function onMqDisconnect($server, int $fd, $fromId, $data): bool
    {
        // TODO: Implement onMqDisconnect() method.
    }

    public function onMqPublish($server, int $fd, $fromId, $data)
    {
        // TODO: Implement onMqPublish() method.
    }

    public function onMqSubscribe($server, int $fd, $fromId, $data)
    {
        // TODO: Implement onMqSubscribe() method.
    }

    public function onMqUnsubscribe($server, int $fd, $fromId, $data)
    {
        // TODO: Implement onMqUnsubscribe() method.
    }
}