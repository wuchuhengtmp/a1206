<?php
/**
 * 请求格式错误
 * @package App\Exceptions\WsExceptions
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Exceptions\WsExceptions;

class RequestFormatException extends BaseException
{
    public $errorMsg = '请求格式错误';
}