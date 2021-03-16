<?php
/**
 * JWT验证
 * @package Utils
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace Utils;

use App\Contracts\JWTContract;

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
        $singing = hash('sha256',  self::$_secrit . json_encode($header) . json_encode($payload));
        return base64_encode(json_encode($header)) . "." .
            base64_encode(json_encode($payload)) . "." .
            base64_encode($singing);
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
        $header = base64_decode($header);
        $payload = base64_decode($payload);
        $signing = base64_decode($signing);
        $trustSigingin = hash('sha256',  self::$_secrit . $header . $payload);
        return $signing === $trustSigingin
            && Helper::isJson($payload)
            && array_key_exists('exp', json_decode($payload, true))
            && json_decode($payload, true)['exp'] >= time();
    }
}