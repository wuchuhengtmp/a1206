<?php
/**
 * 约束事件实例化的参数
 *
 * @package App\Events\MqttEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\MqttEvents;

use Symfony\Contracts\EventDispatcher\Event;

class BaseEvent extends Event
{
    public $fd;

    public function __construct(int $fd)
    {
        $this->fd = $fd;
    }
}