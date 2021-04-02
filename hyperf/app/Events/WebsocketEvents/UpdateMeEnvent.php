<?php
/**
 * 修改用户信息
 * @package App\Events\WebsocketEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

class UpdateMeEnvent extends BaseEvent
{
    const NAME = "ws:put /me";
}