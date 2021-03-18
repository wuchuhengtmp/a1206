<?php
/**
 * 鉴权
 * @package App\Validations\WsValidations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

use App\Events\WebsocketEvents\BaseEvent;
use App\Exceptions\WsExceptions\FrontEndException;
use FastRoute\DataGenerator;
use Utils\JWT;
use Utils\WsMessage;

class AuthValidation extends BaseValidation
{
    public function goCheck(BaseEvent $event): void
    {
        $cMsg = WsMessage::getMsgByEvent($event)->res;
        $auKey =  'authorization';
        $token = '';
        foreach ($cMsg as $key => $v) {
            if (strtoupper($key) === strtoupper($auKey)) {
                $token = $v;
            }
        }
        if ($token === '')  {
            $e = new FrontEndException('the token can\'t be empty');
            $e->url = $cMsg['url'];
            $e->method = $cMsg['method'];
            throw $e;
        }
        preg_match("/[B|b]earer\s(.+)/", $token, $res);
        if (!array_key_exists(1, $res)) {
            $e = new FrontEndException('the token can\'t be empty');
            $e->url = $cMsg['url'];
            $e->method = $cMsg['method'];
            throw $e;
        }
        $token =  $res[1];
        if (!JWT::check($token)) {
            $e = new FrontEndException('the token was invalid');
            $e->url = $cMsg['url'];
            $e->method = $cMsg['method'];
            throw $e;
        }
    }
}