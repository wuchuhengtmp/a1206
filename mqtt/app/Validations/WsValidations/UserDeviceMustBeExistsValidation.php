<?php
/**
 * 设备用户必须有这个设备
 * @package App\Validations\WsValidations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

use App\Events\WebsocketEvents\BaseEvent;
use App\Exceptions\WsExceptions\UserException;
use App\Model\DevicesModel;
use App\Model\UsersModel;
use Utils\JWT;

class UserDeviceMustBeExistsValidation extends BaseValidation
{
    public function goCheck(BaseEvent $event): void
    {
        $me = JWT::getAuthByEvent($event)->res;
        $did = $event->routeParams['id'];
        $deviceModel = new DevicesModel($event->fd);
        $isDevice = $deviceModel->has($deviceModel->tableName, ['user_id' => $me['id'], 'id' => $did]);
        $e = new UserException('没有这个设备');
        $e->url = $event->url;
        $e->method = $event->method;
        if (!$isDevice) throw $e;
    }
}