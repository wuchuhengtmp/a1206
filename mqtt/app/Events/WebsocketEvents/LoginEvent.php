<?php
/**
 * 登录
 * @package App\Events\WebsocketEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

use App\Events\BaseEvent;

class LoginEvent extends BaseEvent
{
    const NAME = 'ws.login';
}