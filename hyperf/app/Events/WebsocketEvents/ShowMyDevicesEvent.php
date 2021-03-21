<?php
/**
 * 获取我的设备事件
 * @package App\Events\WebsocketEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

class ShowMyDevicesEvent extends BaseEvent
{
    const NAME = 'ws.api./me/devices';
}