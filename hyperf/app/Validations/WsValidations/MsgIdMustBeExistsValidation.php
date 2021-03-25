<?php
/**
 * 消息不能为空
 * @package App\Validations\WsValidations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

use App\Events\WebsocketEvents\BaseEvent;
use App\Exception\WsExceptions\FrontEndException;
use Utils\WsMessage;
use Hyperf\WebSocketServer\Context;

class MsgIdMustBeExistsValidation extends BaseValidation
{
    public function goCheck(BaseEvent $event): void
    {
        $data = WsMessage::getMsgByEvent($event)->res;
        if (!array_key_exists('msgid', $data)) {
            $e = new FrontEndException('msgid 消息id不能为空');
            $e->url = $event->url;
            $e->method = $event->method;
            throw $e;
        }
    }
}