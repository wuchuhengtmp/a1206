<?php
/**
 * websocket 异常基类
 * @package App\Exceptions\WsExceptions
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Exception\WsExceptions;

use App\Events\MqttEvents\BaseEvent;

class BaseException extends \Exception implements \Throwable
{
    public $errorCode = 1;

    public $errorMsg = '系统错误';

    public $url = "";

    public $method = "";

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $this->errorMsg = strlen($message) === 0 ? $this->errorMsg : $message;
        $this->errorCode = $code === 0 ? $this->errorCode : $code;
        parent::__construct($this->errorMsg, $this->errorCode, $previous);
    }
}