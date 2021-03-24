<?php
/**
 * Class AddConfigTimeEvent
 * @package App\Events\WebsocketEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

class AddConfigTimeEvent extends BaseEvent
{
    const NAME = 'ws:post./me/devices/:id/configTime';
}