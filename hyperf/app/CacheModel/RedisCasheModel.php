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
use function GuzzleHttp\Promise\queue;

class RedisCasheModel extends BaseAbstract
{
    const PREFIX = "a1206";
    const MSGINFOKEY = self::PREFIX . ":msgInfo";
    const TAG_DEVICE_ONLINE_KEY = self::PREFIX . ':fileQueue:deviceLastOnline';
    const DEVICE_FILE_QUEUE_KEY = self::PREFIX . ':fileQueue:deviceFileQueue';
    const LAST_DEVICE_FILE = self::PREFIX . ':fileQueue:lastDeviceFile';

    public function construct()
    {

    }


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
        return true;
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

    /**
     * 标记设备为在线状态，用于发送消息检验设备是否在线的重要凭据
     * @param string $deviceId
     */
    public function tagDeviceOnline(string $deviceId): void
    {
        $redis = $this->_getClient();
        $redis->hSet(self::TAG_DEVICE_ONLINE_KEY, $deviceId, date('Y-m-d H:i:s', time()));
    }

    /**
     * 设备是否在线
     * @return bool
     */
    public function isDeviceOnlineByDeviceId(string $deviceId): bool
    {

        $redis = $this->_getClient();
        if (!$redis->exists(self::TAG_DEVICE_ONLINE_KEY)) {
            return false;
        } else if (!$redis->hExists(self::TAG_DEVICE_ONLINE_KEY, $deviceId)) {
            return false;
        }
        $lastAckTime = strtotime($redis->hGet(self::TAG_DEVICE_ONLINE_KEY, $deviceId));
        $timeout = (int) env('HYPERF_DEVICE_TIMEOUT');
        if (time() <= $lastAckTime + $timeout) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 设备接收文件是否处于空闲状态
     * @param string $deviceId
     * @return bool
     */
    public function isDeviceFreeByDeviceId(string $deviceId): bool
    {
        $redis = $this->_getClient();
        if(!$this->isDeviceOnlineByDeviceId($deviceId)) {
            return false;
        }
        if (!$redis->exists(self::DEVICE_FILE_QUEUE_KEY) || !$redis->hExists(self::DEVICE_FILE_QUEUE_KEY, $deviceId)) {
            return true;
        }
        $deviceQueue = json_decode($redis->hGet(self::DEVICE_FILE_QUEUE_KEY, $deviceId), true);
        return $deviceQueue['isFree'];
    }
    /**
     * 把设备标记为空闲状态
     * @param string $deviceId
     */
    public function tagDeviceQueueToFree(string $deviceId): void
    {
        $data = [
            'isFree' => true,
            'queue' => []
        ];
        $redis = $this->_getClient();
        $key = self::DEVICE_FILE_QUEUE_KEY;
        if ($redis->exists($key) && $redis->hExists($key, $deviceId)) {
            $oldData = json_decode($redis->hGet($key, $deviceId), true);
            $data['queue'] = $oldData['queue'];
        }
        $redis->hSet($key, $deviceId, json_encode($data));
    }

    /**
     * 把设备标记为非空闲状态
     * @param string $deviceId
     */
    public function tagDeviceQueueToBusy(string $deviceId): void
    {
        $data = [
            'isFree' => false,
            'queue' => []
        ];
        $redis = $this->_getClient();
        $key = self::DEVICE_FILE_QUEUE_KEY;
        if ($redis->exists($key) && $redis->hExists($key, $deviceId)) {
            $oldData = json_decode($redis->hGet($key, $deviceId), true);
            $data['queue'] = $oldData['queue'];
        }
        $redis->hSet($key, $deviceId, json_encode($data));
    }

    /**
     * 添加上传队列文件
     * @param string $topic
     * @param string $message
     * @param array $device
     * @param string $msgid
     * @param array $data
     */
    public function addUploadFileQueue(
        string $topic,
        string $message,
        array $device,
        string $msgid,
        array $data
    ): void
    {
        $redis = $this->_getClient();
        $oldItems = [
            'isFree' => true,
            'queue' => []
        ];

        if (
            $redis->exists(self::DEVICE_FILE_QUEUE_KEY) &&
            $redis->hExists(self::DEVICE_FILE_QUEUE_KEY, $device['device_id'])
        ) {
            $oldInfo = json_decode( $redis->hGet(self::DEVICE_FILE_QUEUE_KEY, $device['device_id']), true);
            $oldItems['isFree'] = $oldInfo['isFree']?? true;
            $oldItems['queue'] = $oldInfo['queue'];
        }
        $oldItems['queue'][] = [
            'topic' => $topic,
            'message' => $message,
            'device' => $device,
            'msgid' => $msgid,
            'data' => $data,
        ];
        $redis->hSet(self::DEVICE_FILE_QUEUE_KEY, $device['device_id'], json_encode([
            'isFree' => $oldItems['isFree'],
            'queue' => $oldItems['queue'],
        ]));
    }

    /**
     * 获取未上传的文件列表
     * @param string $deviceId
     * @return array
     */
    public function getUploadFileQueueByDeviceId(string $deviceId): array
    {
        $queue = [];
        $key = self::DEVICE_FILE_QUEUE_KEY;
        $redis = $this->_getClient();
        if ($redis->exists($key) && $redis->hExists($key, $deviceId)) {
            $data = json_decode($redis->hGet($key, $deviceId), true);
            $queue = $data['queue'];
        }
        return $queue;
    }

    /**
     * 出栈第一个文件
     * @param string $deviceId
     * @param array $queue
     */
    public function shiftUploadFile2Queue(string $deviceId): void
    {
        $queue = $this->getUploadFileQueueByDeviceId($deviceId);
        array_shift($queue);
        $redis = $this->_getClient();
        $initData = [
            'isFree' => true,
            'queue' => $queue
        ];
        $key = self::DEVICE_FILE_QUEUE_KEY;
        if ($redis->exists($key) && $redis->hExists($key, $deviceId)) {
            $oldData = json_decode($redis->hGet($key, $deviceId), true);
            $initData['isFree'] = $oldData['isFree'];
        }
        $redis->hSet($key, $deviceId, json_encode($initData));
    }

    /**
     *  能否把文件队列重置为空闲状态
     * @param string $deviceId
     * @return bool
     */
    public function canResetUploadQueueByDeviceId(string $deviceId): bool
    {
        // 最大次数尝试重新上传
        $maxUploadTime = (int) env('MAX_UPLOAD_TIMES');
        $key = self::LAST_DEVICE_FILE;
        $redis = $this->_getClient();
        if (!$redis->exists($key) || !$redis->hExists($key, $deviceId)) {
            return true;
        }
        $fileLog = json_decode($redis->hGet($key, $deviceId), true);
        $lastTime = strtotime($fileLog['time']);
        // 删除哪个尝试上传失败的文件队列
        if ($fileLog['total'] >= $maxUploadTime) {
            $this->shiftUploadFile2Queue($deviceId);
            return true;
        }
        $fileLog['total']++;
        $redis->hSet($key, $deviceId, json_encode($fileLog));
        return $lastTime + 60 < time();
    }

    /**
     *  设置文件的发送时间
     * @param string $deviceId
     */
    public function setLastFileSendTimeByDeviceId(string $deviceId): void
    {
        $key = self::LAST_DEVICE_FILE;
        $redis = $this->_getClient();
        $t = date('Y-m-d H:i:s', time());
        $redis->hSet($key, $deviceId, json_encode(['time' => $t, 'total' => 1]));
    }
}