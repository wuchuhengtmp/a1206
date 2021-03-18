<?php
/**
 * 上报事件
 * @package App\Events\MqttEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\MqttEvents;

class ReportEvent extends BaseEvent
{
    const NAME = 'mqtt.report';
}