<?php
declare(strict_types=1);

/**
 * 登录前事件
 * @package App\Events\MqttEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */

namespace App\Events\MqttEvents;

use App\Contracts\MqttEventInterface;
use Symfony\Contracts\EventDispatcher\Event;

class LoginEvent extends BaseEvent
{
    const NAME = 'mqtt.login';
}