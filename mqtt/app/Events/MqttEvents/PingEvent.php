<?php
/**
 * Class PingEvent
 * @package App\Events\MqttEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\MqttEvents;

class PingEvent extends BaseEvent
{
    const NAME = 'mqtt.ping';
}