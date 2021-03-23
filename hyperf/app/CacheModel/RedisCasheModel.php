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
use phpDocumentor\Reflection\Types\Mixed_;
use Utils\Helper;

class RedisCasheModel extends BaseAbstract
{
    public $prefix = "a1206";

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
        $redis->hSet($this->prefix . ":keys", $key, $value);
    }

    public function get(string $key)
    {
        $v = $this->_getClient()->hGet($this->prefix . ":keys", $key);
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
        return $redis->hExists($this->prefix . ":kyes", $key);
    }

    public function setConnectInfo(string $key, array $value)
    {
        $value = json_encode($value);
        $redis = $this->_getClient();
        $redis->hset($this->prefix . ":clientIdMapConnectInfo", $key, $value);
    }

    public function getConnectInfoByClientId(string $key): array
    {
        $redis = $this->_getClient();
        $data = $redis->hget($this->prefix . ":clientIdMapConnectInfo", $key);
        return json_decode($data, true);
    }

    public function setRegisterInfo(string $key, array $value)
    {
        $value = json_encode($value);
        $redis = $this->_getClient();
        $redis->hset($this->prefix . ":clientIdMapRegisterInfo", $key, $value);
    }

    public function getRegisterInfoByClientId(string $key): array
    {
        $redis = $this->_getClient();
        $data = $redis->hget($this->prefix . ":clientIdMapRegisterInfo", $key);
        return \json_decode($data, true);
    }
}