<?php
/**
 * 设备详情事件
 * @package App\Events\WebsocketEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

class ShowDeviceDetailEvent extends BaseEvent
{
    const NAME = 'ws.api./me/devices/:id';
}