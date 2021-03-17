<?php
/**
 * JWT验证
 * @package Utils
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace Utils;

use App\Contracts\JWTContract;
use App\Events\WebsocketEvents\BaseEvent;
use App\Model\UsersModel;

class JWT implements JWTContract
{
    //  token 有效果时长
    static private $_exp = 60 * 60 * 24 * 365;

    // 密钥
    static private $_secrit = 'sflkasdjfakl; jasdkl;f jadskl fjasdklf jadskljf lkasjf lasdkjf ;laksdjf kl;asjf as';

    /**
     * @param int $uid
     * @return string
     */
    static public function generate(int $uid): string
    {
        $header = [
            'type' => 'JWT',
            'alg' => 'HS256',
        ];
        $payload =  [
            'exp' => time() + self::$_exp,
            'uid' => $uid
        ];
        $header = base64_encode(json_encode($header));
        $payload = base64_encode(json_encode($payload));
        $singing = hash('sha256',  self::$_secrit . $header . $payload);
        return sprintf("%s.%s.%s", $header, $payload, $singing);
    }

    /**
     * @param string $signing
     * @return bool
     */
    static public function check(string $signing): bool
    {
        $arr = explode('.', $signing);
        if( count($arr) !== 3 ) return false;
        list($header, $payload, $signing) = $arr;
        $trustSigingin = hash('sha256',  self::$_secrit . $header . $payload);
        $header = base64_decode($header);
        $payload = base64_decode($payload);
        $isAccess = $signing === $trustSigingin
            && Helper::isJson($payload)
            && array_key_exists('exp', json_decode($payload, true))
            && json_decode($payload, true)['exp'] >= time();
        return $isAccess;
    }

    /**
     * @param BaseEvent $event
     * @return ReportFormat
     */
    static public function getAuthByEvent(BaseEvent $event): ReportFormat
    {
        $returnData = new ReportFormat();
        $hasMsg = WsMessage::getMsgByEvent($event);
        if ($hasMsg->isError) return $hasMsg;
        $msg = $hasMsg->res;
        if (!array_key_exists('authorization', $msg)) return $res;
        preg_match('/^[B|b]earer\s(.+)/', $msg['authorization'], $res);
        if (!array_key_exists(1, $res)) return $res;
        $token = $res[1];
        if (!self::check($token)) return $res;
        $payload = json_decode(base64_decode(explode('.', $token)[1]), true);
        $uid = $payload['uid'];
        $userModel = new UsersModel($event->fd);
        if ($userModel->hasUser(['id' => $uid])) {
            $returnData->res = $userModel->getUserById($uid);
            $returnData->isError = false;
            return $returnData;
        } else {
            return $returnData;
        }

    }
}