<?php
/**
 * 约束事件实例化的参数
 *
 * @package App\Events\MqttEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

use Symfony\Contracts\EventDispatcher\Event;

class BaseEvent extends Event
{
    public $fd;

    public $method;

    public $url;

    public $messageId;

    public function __construct(int $fd, string $method = '', string $url = '')
    {
        $this->fd = $fd;
        $this->method = $method;
        $this->url = $url;
        $this->messageId =  $fd . "|" . $this->method . "|" . $this->url . "|" . $messageId = microtime();
    }
}