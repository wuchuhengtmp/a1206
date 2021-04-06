<?php
/**
 * 更新设备属性
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\UpdateDevicePropertyEvent;
use App\Model\DevicesModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\WsMessage;

class UpdateDevicePropertySubscript implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
         return [
             UpdateDevicePropertyEvent::NAME => 'handle'
         ];
    }

    public function handle(BaseEvent  $event)
    {
        $data = WsMessage::getMsgByEvent($event)->res['data'];
        $device = DevicesModel::query()->where('id', $event->routeParams['id'])->first();
        $device->category_id = $data['category_id'];
        $device->alias = $data['alias'];
        if ($device->save())  {
            WsMessage::resSuccess($event);
        } else {
            WsMessage::resError($event, ['errorMsg' => '操作失败']);
        }
    }
}