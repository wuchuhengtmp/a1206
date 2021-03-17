<?php
/**
 * 获取我的设备
 * @package App\Listens\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\ShowMyDevicesEvent;
use App\Model\DevicesModel;
use mysql_xdevapi\BaseResult;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\WsMessage;

class ShowMyDevicesSubscript implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ShowMyDevicesEvent::NAME => 'handle'
        ];
    }

    public function handle(BaseEvent  $event): void
    {
        $me = $event->getAuth()->res;
        $devices = (new DevicesModel($event->fd))->getDevicesByUid((int) $me['id']);
        WsMessage::resSuccess($event, $devices);
    }
}