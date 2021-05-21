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
use App\Model\UsersModel;
use Utils\JWT;
use Utils\WsMessage;

class DeviceMustBeExistsValidation extends BaseValidation
{
    public function goCheck(BaseEvent $event): void
    {
        $data = WsMessage::getMsgByEvent($event)->res;
        if (!array_key_exists('data', $data) || !array_key_exists('deviceId', $data['data'])) {
            $e = new UserException('设备参数不能为空');
            $e->url = $event->url;
            $e->method = $event->method;
            throw $e;
        }
        $deviceModel = new DevicesModel();
        $isDevice = $deviceModel->where('device_id',  $data['data']['deviceId'])->get()->isNotEmpty();
        $e = new UserException('没有这个设备');
        $e->url = $event->url;
        $e->method = $event->method;
        if (!$isDevice) throw $e;
    }
}