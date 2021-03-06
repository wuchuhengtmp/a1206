<?php
/**
 * 后端引发的异常
 * @package App\Exception\WsExceptions
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Exception\WsExceptions;

class BackEndException extends BaseException
{

    public $errorCode = 2;

    public $errorMsg = '系统错误,请联系后台管理员';
}