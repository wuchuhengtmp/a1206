<?php
/**
 * Class RejectConnectEvent
 * @package App\Events\MqttEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\MqttEvents;

use Symfony\Contracts\EventDispatcher\Event;

class RejectConnectEvent extends Event
{
    const NAME = 'mqtt.rejectConnect';
}