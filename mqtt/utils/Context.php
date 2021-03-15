<?php
declare(strict_types=1);

/**
 * 单连接单协程上下文
 *
 * @package Utils
 * @author wuchuheng  <wuchuheng@163.com>
 */

namespace Utils;

use Swoole\Coroutine;

class Context
{
    static private $pool = [];

    static public function save(int $fd, $data = [])
    {
        array_key_exists('server', $data) && self::set($fd, 'server', $data['server']);
        array_key_exists('fd', $data) && self::set($fd, 'fd', $data['fd']);
        array_key_exists('fromId', $data) && self::set($fd, 'fromId', $data['fromId']);
        array_key_exists('data', $data) && self::set($fd, 'data', $data['data']);
    }

    static public function set(int $fd, string $key, $value): void
    {
        $cid = $fd;
        if ($cid) {
            self::$pool[$cid][$key] = $value;
        }
    }

    static public function get(int $fd, string $key): ReportFormat
    {
        $res = new ReportFormat();
        $cid = $fd;
        if (array_key_exists($cid, self::$pool) && array_key_exists($key, self::$pool[$cid])) {
            $res->isError = false;
            $res->res = self::$pool[$cid][$key];
        }
        return $res;
    }

    static public function getServer(int $fd): ReportFormat
    {
        return self::get($fd, 'server');
    }

    static public function getData(int $fd): ReportFormat
    {
        return self::get($fd, 'data');
    }

    /**
     * 删除整个连接上下文
     */
    static public function deleteConectContext(int $fd)
    {
        unset(self::$pool[$fd]);
    }
}