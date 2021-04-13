<?php
/**
 * 验证设备文件是否存在
 * @package App\Validations\WsValidations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

use App\Events\WebsocketEvents\BaseEvent;
use App\Exception\WsExceptions\UserException;
use App\Model\DeviceFilesModel;

class DeviceFileMustBeExistsValidation extends BaseValidation
{
    public function goCheck(BaseEvent $event): void
    {
        $deviceId = (int) $event->routeParams['id'];
        $fileId = (int) $event->routeParams['fileId'];
        $file = DeviceFilesModel::query()->where('device_id', $deviceId)->where('id', $fileId)->first();
        if (!$file) {
            $e = new UserException('没有这个设备文件');
            $e->url = $event->url;
            $e->method = $event->method;
            throw $e;
        }
    }
}