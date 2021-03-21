<?php
/**
 * Class MqttClientPingEvent
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MqttClientPingEvent extends BaseEvent
{
    const NAME = 'ws:mqttClient.ping';
}