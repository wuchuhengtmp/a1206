<?php
/**
 * 全局管道
 * @package App
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App;

use Co\Channel;
use Symfony\Component\EventDispatcher\EventDispatcher;

class GlobalChannel
{

    static private $_instance = null;

    static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new Channel(10);
        }
        return self::$_instance;
    }
}