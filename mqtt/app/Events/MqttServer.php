<?php

declare(strict_types=1);

namespace App\Events;


use App\Dispatcher;
use App\Events\MqttEvents\LoginEvent;
use Simps\Server\Protocol\MqttInterface;
use \Simps\DB\BaseRedis;

class MqttServer implements MqttInterface
{

    public function onMqConnect($server, int $fd, $fromId, $data)
    {
        // 登录事件
        Dispatcher::getInstance()->dispatch(new LoginEvent($server, $fd, $fromId, $data), LoginEvent::NAME);
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