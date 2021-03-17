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

    /** 当前连接消息标识号
     *  用于获取当前消息用的
     * @var string
     */
    public $messageId;

    public $currentMsg;

    private $_hasCurrentMsg = false;

    private $_server;

    public function __construct(int $fd, array $currentMsg = [], $server = null)
    {
        $this->fd = $fd;
        $this->messageId =  $fd . "|" . date("Y-m-d H:i:s", time()) . "|" . uniqid();
        if (count($currentMsg) > 0) {
            $this->_hasCurrentMsg = true;
            $this->currentMsg = $currentMsg;
        }
        if ($server !== null) {
            $this->_server = $server;
        }
    }

    /**
     * 是否包含当前消息
     * @return bool
     */
    public function hasCurrentMsg(): bool
    {
        return $this->_hasCurrentMsg;
    }

    /**
     * @return bool
     */
    public function hasServer(): bool
    {
        return $this->_server !== null;
    }

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->_server;
    }
}