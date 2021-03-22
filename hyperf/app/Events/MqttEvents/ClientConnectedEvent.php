<?php
/**
 * 用户连接
 * @package App\Events\MqttEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\MqttEvents;

class ClientConnectedEvent
{
    public $data;

    public function __construct(object $data)
    {
        $this->data = $data;
    }
}