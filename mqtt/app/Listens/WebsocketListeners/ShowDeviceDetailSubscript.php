<?php
/**
 * Class ShowDeviceDetailSubscript
 * @package App\Listens\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\ShowDeviceDetailEvent;
use App\Model\DevicesModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\JWT;
use Utils\WsMessage;

class ShowDeviceDetailSubscript implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ShowDeviceDetailEvent::NAME => 'handle'
        ];
    }

    /**j
     * 设备详情
     * @param BaseEvent $event
     */
    public function handle(BaseEvent $event): void
    {
        $user = JWT::getAuthByEvent($event)->res;
        $did = $event->routeParams['id'];
        $dm = (new DevicesModel($event->fd));
        $columns = [
            'id',
            'status',
            'play_state',
            'play_mode',
            'play_sound',
            'file_cnt',
            'file_current',
            'play_timer_sum',
            'play_timer_cur',
            'memory_size',
            'trigger_modes',
            'battery_vol'
        ];
        $detail = $dm->get($dm->tableName, $columns, ['id' => $did, 'user_id' => $user['id']]);
        $detail['trigger_modes'] = \json_decode($detail['trigger_modes'], true);
        WsMessage::resSuccess($event, $detail);
    }
}