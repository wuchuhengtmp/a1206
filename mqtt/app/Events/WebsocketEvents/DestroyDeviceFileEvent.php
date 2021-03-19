<?php
/**
 * Class DestroyDeviceFileEvent
 * @package App\Events\WebsocketEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

class DestroyDeviceFileEvent extends BaseEvent
{
    const NAME = 'ws.delete.api./me/devices/:id';
}