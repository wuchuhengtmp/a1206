<?php
/**
 * 解除绑定一个设备
 * @package App\Events\WebsocketEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

class DeviceUnbindUser extends BaseEvent
{
    const NAME = 'ws:delete /me/devices/:id';
}