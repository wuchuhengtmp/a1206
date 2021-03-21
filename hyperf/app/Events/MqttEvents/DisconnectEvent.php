<?php
/**
 * 断开前事件
 * @package App\Events\MqttEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\MqttEvents;

use Symfony\Contracts\EventDispatcher\Event;

class DisconnectEvent extends BaseEvent
{
    const NAME = 'mqtt.disconnect';
}