<?php
/**
 * 关于我们
 * @package App\Events\WebsocketEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

class AboutEvent extends BaseEvent
{
    const NAME = 'ws:get./about';
}