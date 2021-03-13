<?php

declare(strict_types=1);

namespace App\Events;


use App\Dispatcher;
use App\Events\MqttEvents\DisconnectEvent;
use App\Events\MqttEvents\LoginEvent;
use App\Events\MqttEvents\LoggedEvent;
use Simps\Server\Protocol\MqttInterface;
use Utils\Context;
use Utils\Message;

class MqttServer implements MqttInterface
{

    public function onMqConnect($server, int $fd, $fromId, $data)
    {
        Context::save(['server' => $server, 'fd' => $fd, 'fromId' => $fromId, 'data' => $data]);
        // 登录
        Dispatcher::getInstance()->dispatch(new LoginEvent(), LoginEvent::NAME);
    }

    public function onMqPingreq($server, int $fd, $fromId, $data): bool
    {

        // TODO: Implement onMqPingreq() method.
    }

    public function onMqDisconnect($server, int $fd, $fromId, $data): bool
    {
    }

    public function onMqPublish($server, int $fd, $fromId, $data)
    {
        Context::save(['server' => $server, 'fd' => $fd, 'fromId' => $fromId, 'data' => $data]);
        $res = Message::getCommand();
        if ($res->isError === false) {

        }
    }

    public function onMqSubscribe($server, int $fd, $fromId, $data)
    {
        // TODO: Implement onMqSubscribe() method.
    }

    public function onMqUnsubscribe($server, int $fd, $fromId, $data)
    {
    }

    /**
     *  断开
     * @param $server
     * @param int $fd
     * @param $fromId
     * @param $data
     */
    static public function onClose($server, int $fd, $fromId): void
    {
        Dispatcher::getInstance()->dispatch(new DisconnectEvent(), DisconnectEvent::NAME);
    }
}