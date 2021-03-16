<?php
/**
 * 前端开发者引发的异常
 * @package App\Exceptions\WsExceptions
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Exceptions\WsExceptions;

class FrontEndException extends BaseException
{
    public $errorCode = 2;

    public $errorMsg = '操作失败, 请联系前端开发者';
}