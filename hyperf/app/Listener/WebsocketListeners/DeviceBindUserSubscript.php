<?php
/**
 * 获取分类
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\DeviceBindUser;
use App\Model\CategoriesModel;
use App\Model\DevicesModel;
use Hyperf\Utils\ApplicationContext;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Events\WebsocketEvents\ShowCategoriesEvent;
use Utils\WsMessage;

class DeviceBindUserSubscript implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            DeviceBindUser::NAME => 'handle'
        ];
    }

    public function handle(BaseEvent $event): void
    {
        $deviceId = WsMessage::getMsgByEvent($event)->res['data']['deviceId'];
        $me = $event->getAuth()->res;
        $device = DevicesModel::where('device_id', $deviceId)->first();
        $device->user_id = $me['id'];
        $device->save();
        WsMessage::resSuccess($event, []);
    }
}