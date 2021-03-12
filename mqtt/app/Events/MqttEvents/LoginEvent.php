<?php
declare(strict_types=1);

/**
 * 登录事件
 * @package App\Events\MqttEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */

namespace App\Events\MqttEvents;

use App\Contracts\MqttEventInterface;
use Symfony\Contracts\EventDispatcher\Event;

class LoginEvent  extends Event implements MqttEventInterface
{
    const NAME = 'mqtt.validateLogin';

    protected $server;

    protected $fd;

    protected $fromId;

    protected $data ;


    public function __construct($server, int $fd, $fromId, $data)
    {
        $this->server = $server;
        $this->fd = $fd;
        $this->fromId = $fromId;
        $this->data = $data;
    }

    /**
     * @return Object|null
     */
    public function getServer(): ?Object
    {
        return $this->server;
    }

    /**
     * @return int
     */
    public function getFd(): int
    {
        return $this->fd;
    }

    /**
     * @return int|null
     */
    public function getFromId(): ?int
    {
        return $this->fromId;
    }

    /**
     * @return Array|null
     */
    public function getData()
    {
        return $this->data;
    }
}