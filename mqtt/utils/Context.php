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


    static public function save($data = [])
    {
        array_key_exists('server', $data) && self::set('server', $data['server']);
        array_key_exists('fd', $data) && self::set('fd', $data['fd']);
        array_key_exists('fromId', $data) && self::set('fromId', $data['fromId']);
        array_key_exists('data', $data) && self::set('data', $data['data']);
    }

    static public function set(string $key, $value): void
    {
        $cid = Coroutine::getuid();
        if ($cid) {
            self::$pool[$cid][$key] = $value;
        }
    }

    static public function get(string $key)
    {
        $cid = Coroutine::getuid();
        if ($cid && array_key_exists($key, self::$pool[$cid])) {
            return self::$pool[$cid][$key];
        }
    }

    static public function getServer()
    {
        $cid = Coroutine::getuid();
        return self::$pool[$cid]['server'];
    }

    static public function getFd(): int
    {
        $cid = Coroutine::getuid();
        return self::$pool[$cid]['fd'];
    }

    static public function getData()
    {
        $cid = Coroutine::getuid();
        return self::$pool[$cid]['data'];
    }

    static public function getFromId()
    {
        $cid = Coroutine::getuid();
        return self::$pool[$cid]['fromId'];
    }

    /**
     * 删除整个连接上下文
     */
    static public function deleteConectContext()
    {
        $cid = Coroutine::getuid();
        unset(self::$pool[$cid]);
    }
}