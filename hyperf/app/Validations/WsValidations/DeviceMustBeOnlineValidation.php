<?php
/**
 * 设备在线验证
 * @package App\Validations\WsValidations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

use App\Events\WebsocketEvents\BaseEvent;
use App\Exception\WsExceptions\UserException;
use App\Model\DevicesModel;
use Utils\WsMessage;

class DeviceMustBeOnlineValidation extends BaseValidation
{
    public function goCheck(BaseEvent $event): void
    {
        $data = WsMessage::getMsgByEvent($event)->res;
        $devcieId = $event->routeParams['id'];
        $isOnline = DevicesModel::query()->where('id', $devcieId)
            ->where('status', 'online')
            ->get()
            ->isNotEmpty();
        if (!$isOnline) {
            $e = new UserException('设备不在线');
            $e->url = $event->url;
            $e->method = $event->method;
            throw $e;
        }
    }
}