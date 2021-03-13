<?php
/**
 * 订阅设备注册事件
 *
 * @package App\Listens\MqttListens
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\MqttListens;

use App\Events\MqttEvents\RegisterEvent;
use Simps\DB\BaseModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\Message;

class RegisterSubscript extends BaseModel implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            RegisterEvent::NAME => 'handle'
        ];
    }

    public function handle(RegisterEvent $event)
    {
        $hasConnectMsg = Message::getConnectMsg($event->fd);
        var_dump($hasConnectMsg->res);
        var_dump($hasConnectMsg);
    }
}