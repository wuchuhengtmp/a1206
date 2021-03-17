<?php
/**
 * 获取分类
 * @package App\Events\MqttEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

class ShowCategoriesEvent extends \App\Events\WebsocketEvents\BaseEvent
{
    const NAME = 'ws.api./categories';
}