<?php
/**
 * @package App\Events\WebsocketEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

class ResetMePasswordEvent extends BaseEvent
{
    const NAME = 'ws:put./me/password';
}