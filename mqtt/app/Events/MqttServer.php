<?php

declare(strict_types=1);

namespace App\Events;


use App\Dispatcher;
use App\Events\MqttEvents\BaseEvent;
use App\Events\MqttEvents\DisconnectEvent;
use App\Events\MqttEvents\LoginEvent;
use App\Events\MqttEvents\PingEvent;
use App\Events\MqttEvents\RegisterEvent;
use App\Events\MqttEvents\ReportEvent;
use App\Events\MqttEvents\SubscriptEvent;
use Simps\Server\Protocol\MQTT;
use Simps\Server\Protocol\MqttInterface;
use Swoole\Server;
use Utils\Context;
use Utils\Message;

class MqttServer implements MqttInterface
{

    public function onMqConnect($server, int $fd, $fromId, $data)
    {
        Message::setConnectMsg($fd, $data);
        Context::save($fd, ['server' => $server, 'fd' => $fd, 'fromId' => $fromId, 'data' => $data]);
        // 登录
        Dispatcher::getInstance()->dispatch(new LoginEvent($fd, $data, $server), LoginEvent::NAME);
    }

    public function onMqPingreq($server, int $fd, $fromId, $data): bool
    {
        Context::save($fd, ['server' => $server, 'fd' => $fd, 'fromId' => $fromId, 'data' => $data]);
        Dispatcher::getInstance()->dispatch(new PingEvent($fd, $data, $server), PingEvent::NAME);
        return true;
    }

    public function onMqDisconnect($server, int $fd, $fromId, $data): bool
    {
        return true;
    }

    public function onMqPublish($server, int $fd, $fromId, $data)
    {
        Context::save($fd, ['server' => $server, 'fd' => $fd, 'fromId' => $fromId, 'data' => $data]);
        $res = Message::getCommand($fd);
        if ($res->isError === false) {
            switch ($res->res) {
                // 发布注册事件
                case 'register':
                    Message::setRegister($fd, $data);
                    Dispatcher::getInstance()->dispatch(new RegisterEvent($fd, $data, $server), RegisterEvent::NAME);
                    break;
                // 发布上报事件
                case 'report_data':
                    Dispatcher::getInstance()->dispatch(new ReportEvent($fd, $data, $server), ReportEvent::NAME);
                    break;
                default:
                    var_dump($res->res);
            }
        }
    }

    public function onMqSubscribe($server, int $fd, $fromId, $data)
    {
        Message::setSubscriptMsg($fd, $data);
        Dispatcher::getInstance()->dispatch(new SubscriptEvent($fd, $data, $server), SubscriptEvent::NAME);
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
        Dispatcher::getInstance()->dispatch(new DisconnectEvent($fd, [], $server), DisconnectEvent::NAME);
    }
}