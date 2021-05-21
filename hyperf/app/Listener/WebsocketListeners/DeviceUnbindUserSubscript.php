<?php
/**
 * 解除绑定
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\DeviceBindUser;
use App\Events\WebsocketEvents\DeviceUnbindUser;
use App\Model\CategoriesModel;
use App\Model\DevicesModel;
use Hyperf\Utils\ApplicationContext;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Events\WebsocketEvents\ShowCategoriesEvent;
use Utils\WsMessage;

class DeviceUnbindUserSubscript implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            DeviceUnbindUser::NAME => 'handle'
        ];
    }

    public function handle(BaseEvent $event): void
    {
        $deviceId = $event->routeParams['id'];
        $device = DevicesModel::where('id', $deviceId)->first();
        $device->user_id = 1;
        $device->save();
        WsMessage::resSuccess($event, []);
    }
}