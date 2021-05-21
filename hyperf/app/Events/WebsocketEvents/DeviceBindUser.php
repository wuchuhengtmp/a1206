<?php
/**
 * 绑定一个新的设备
 * @package App\Events\WebsocketEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

class DeviceBindUser extends BaseEvent
{
    const NAME = 'ws:post /me/devices';
}