<?php
/**
 * 心跳事件
 * @package App\Events\WebsocketEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

class PingEvent extends BaseEvent
{
    const NAME = "ws.ping";
}