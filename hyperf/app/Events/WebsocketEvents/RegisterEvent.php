<?php
/**
 * 注册事件
 * @package App\Events\WebsocketEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

class RegisterEvent extends BaseEvent
{
    const NAME='ws:register';
}