<?php
/**
 * 设备必须有这个设备
 * @package App\Validations\WsValidations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

use App\Events\WebsocketEvents\BaseEvent;
use App\Exception\WsExceptions\UserException;
use App\Model\DevicesModel;

class DeviceUnbindUserRequest extends BaseValidation
{
    public function goCheck(BaseEvent $event): void
    {
        $deviceId = $event->routeParams['id'];
        $me = $event->getAuth()->res;
        $isDevice = DevicesModel::where('id',  $deviceId)
            ->where('user_id', $me['id'])
            ->get()
            ->isNotEmpty();
        $e = new UserException('没有这个设备');
        $e->url = $event->url;
        $e->method = $event->method;
        if (!$isDevice) throw $e;
    }
}