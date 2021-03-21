<?php
/**
 * 用户操作不当引发的异常
 * @package App\Exceptions\WsExceptions
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Exception\WsExceptions;

class UserException extends BaseException
{
    public $errorCode = 1;

    public $errorMsg = '执行失败，请检查你的操作是否正确';
}