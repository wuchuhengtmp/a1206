<?php
/**
 * 更新设备事件
 * @package App\Events\WebsocketEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

class UpdateDevicePropertyEvent extends BaseEvent
{
    const NAME = 'ws.patch.api./me/devices/:id';
}
