<?php
/**
 * 连接断开订阅
 * @package App\Listens\MqttListens
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\MqttListens;

use App\Events\MqttEvents\DisconnectEvent;
use Simps\DB\BaseModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\Context;

class DisconnectSubscript extends BaseModel implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            DisconnectEvent::NAME => 'handleDisconnect'
        ];
    }

    /**
     *  断开
     */
    public function handleDisconnect(DisconnectEvent $event)
    {
        Context::deleteConectContext();
    }
}