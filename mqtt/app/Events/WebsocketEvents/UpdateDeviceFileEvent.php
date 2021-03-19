<?php
/**
 * 修改设备文件
 * @package App\Events\WebsocketEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

class UpdateDeviceFileEvent extends BaseEvent
{
    const NAME = 'ws.patch.api./me/devices/:id/files';
}