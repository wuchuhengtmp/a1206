<?php
/**
 * Class RedisCasheModel
 * @package App\CacheModel
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\CacheModel;

use Hyperf\Redis\Redis;
use Hyperf\Utils\ApplicationContext;
use PHPUnit\Util\Test;
use Utils\Helper;

class RedisCasheModel extends BaseAbstract
{
    const PREFIX = "a1206";

    const MSGINFOKEY = self::PREFIX . ":msgInfo";


    private function _getClient(): Redis
    {
        $container = ApplicationContext::getContainer();
        return $container->get(\Hyperf\Redis\Redis::class);
    }

    public function set(string $key, $value): bool
    {
        $redis = $this->_getClient();
        if (is_array($value)) {
            $value = json_encode([
                'type' => 'array',
                'value' => $value
            ]);
        } else if (is_object($value)) {
            $value = json_encode([
                'type' => 'object',
                'value' => $value
            ]);
        }
        $redis->hSet(self::PREFIX . ":keys", $key, $value);
    }

    public function get(string $key)
    {
        $v = $this->_getClient()->hGet(self::PREFIX . ":keys", $key);
        if (Helper::isJson($v)) {
            $v = json_decode($v, true);
            switch ($v['type']) {
                case 'array':
                    (array) $v['value'];
                    break;
                case 'object':
                    (object) $v['object'];
                    break;
            }
        } else {
            return $v;
        }
    }

    public function has(string $key): bool
    {
        $redis = $this->_getClient();
        return $redis->hExists(self::PREFIX . ":kyes", $key);
    }

    public function setConnectInfo(string $key, array $value)
    {
        $value = json_encode($value);
        $redis = $this->_getClient();
        $redis->hset(self::PREFIX . ":clientIdMapConnectInfo", $key, $value);
    }

    public function getConnectInfoByClientId(string $key): array
    {
        $redis = $this->_getClient();
        $data = $redis->hget(self::PREFIX . ":clientIdMapConnectInfo", $key);
        return json_decode($data, true);
    }

    public function setRegisterInfo(string $key, array $value)
    {
        $value = json_encode($value);
        $redis = $this->_getClient();
        $redis->hset(self::PREFIX . ":clientIdMapRegisterInfo", $key, $value);
    }

    public function getRegisterInfoByClientId(string $key): array
    {
        $redis = $this->_getClient();
        $data = $redis->hget(self::PREFIX . ":clientIdMapRegisterInfo", $key);
        return \json_decode($data, true);
    }

    public function uidBindFd(int $uid, int $fd): void
    {
        $uid = (string) $uid;
        $redis = $this->_getClient();
        $fkey = self::PREFIX . ":uidBindFd";
        $data = [];
        if ($redis->hExists($fkey, $uid)) {
            $data = json_decode($redis->hGet( $fkey, $uid), true);
        }
        $data[] = $fd;
        $data = array_unique($data);
        $redis->hset($fkey, (string) $uid, json_encode($data));
        $this->fdBindUid($fd, (int) $uid);
    }

    /**
     * @param int $fd
     * @param int $uid
     */
    public function fdBindUid(int $fd, int $uid): void
    {
        $uid = (string) $uid;
        $redis = $this->_getClient();
        $fkey = self::PREFIX . ":fdBindUid";
        $redis->hset($fkey, (string) $fd, $uid);
    }

    /**
     * 获取用户uid
     * @param int $fd
     * @return int
     */
    public function getUidByFd(int $fd): int
    {
        $fd = (string) $fd;
        $redis = $this->_getClient();
        $fkey = self::PREFIX . ":fdBindUid";
        $foo = $redis->hGet($fkey, $fd);
        return (int) $foo;
    }

    /**
     *  用户uid解绑fd
     * @param int $uid
     * @param int $fd
     */
    public function uidUnbindFd(int $uid, int $fd): void
    {
        $fds = $this->getFdByUid($uid);
        $key = array_search($fd, $fds);
        $fkey = self::PREFIX . ':uidBindFd';
        unset($fds[$key]);
        $redis = $this->_getClient();
        $redis->hSet($fkey, (string) $uid, json_encode($fds));
        $fdKey = self::PREFIX . ":fdBindUid";
        $redis->hDel($fdKey, $fd);
    }

    public function getFdByUid(int $uid): array
    {
        $redis = $this->_getClient();
        $fkey = self::PREFIX . ':uidBindFd';
        $fds = $redis->hget($fkey, (string) $uid);
        return json_decode($fds, true);
    }

    /**
     *  保存控制消息队列
     * @param string $devcieId
     * @param $msg
     */
    public function setControMessage(string $devcieId, int $msgid,  $msg)
    {
        var_dump($msgid);
        $redis = $this->_getClient();
        $key = self::PREFIX . ":devcieMessageQueue";
        $hkey = $devcieId;
        $data = [];
        if ($redis->hExists($key, $hkey)) {
            $oldData = json_decode($redis->hGet($key, $hkey), true);
            $oldData[$msgid] = $msg;
            $data = $oldData;
        } else {
            $data[$msgid] = $msg;
        }
        $redis->hSet($key, $hkey, json_encode($data));
    }

    /**
     * 获取控制消息队列
     * @param string $devcieId
     * @return mixed
     */
    public function getControllerMessage(string $devcieId, int $msgid)
    {
        $redis = $this->_getClient();
        $key = self::PREFIX . ":devcieMessageQueue";
        $hkey = $devcieId;
        $queue = json_decode($redis->hGet($key, $hkey), true);
        $e = $queue[$msgid];
        unset($queue[$msgid]);
        $redis->hSet($key, $hkey, json_encode($queue));
        return  json_decode($e, true);
    }

    /**
     * 设置短信消息
     * @param string $key
     * @param array $data
     */
    public function setMsgInfo(string $key, array $data)
    {
        $redis = $this->_getClient();
        $redis->hSet(self::MSGINFOKEY, $key, json_encode($data));
    }


    /**
     * 获取短信短信验证码缓存
     * @param string $key
     * @return array
     */
    public function getMsgInfo(string $key): array
    {
        $redis = $this->_getClient();
        $data = $redis->hget(self::MSGINFOKEY, $key);
        return json_decode($data, true);
    }

    /***
     *  是否有这个短信验证码缓存
     * @param string $key
     * @return bool
     */
    public function hasMsgInfoByKey(string $key): bool
    {
        $redis = $this->_getClient();
        return (bool) $redis->hExists(self::MSGINFOKEY, $key);
    }

    /**
     *  删除这个短信验证码缓存
     * @param string $key
     * @return bool
     */
    public function delMsgInfoByKey(string $key): bool
    {
        $redis = $this->_getClient();
        return (bool) $redis->hDel(self::MSGINFOKEY, $key);
    }
}