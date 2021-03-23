<?php
/**
 * Class PlayFilesValidation
 * @package App\Validations\WsValidations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

use App\Events\WebsocketEvents\BaseEvent;
use App\Exception\WsExceptions\UserException;

class PlayFilesValidation extends BaseValidation
{
    public function goCheck(BaseEvent $event): void
    {
        $status = $event->routeParams['status'];
        // todo 这里验证设备有没有上下一曲可切换
        // ...
        if (!in_array($status, [ '-1', '-2'])) {
            $e = new UserException('播放切换必须为: -1 （上一曲） 或-2 (下一曲)');
            $e->url = $event->url;
            $e->method = $event->method;
            throw  $e;
        }
    }
}