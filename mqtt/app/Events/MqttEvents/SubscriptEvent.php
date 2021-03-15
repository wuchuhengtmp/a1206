<?php
/**
 * Class SubscriptEvent
 * @package App\Events\MqttEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\MqttEvents;

class SubscriptEvent extends BaseEvent
{
    const NAME = 'mqtt.subscript';
}