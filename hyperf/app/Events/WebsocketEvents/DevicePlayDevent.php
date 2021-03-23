<?php
/**
 * Class DevicePlayDevent
 * @package App\Events\WebsocketEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

class DevicePlayDevent extends BaseEvent
{
    const NAME = 'ws.put./me/devices/:id/play';
}