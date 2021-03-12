<?php
declare(strict_types=1);

/**
 * 事件发布器
 *
 * @author wuchuheng  <wuchuheng@163.com>
 */

namespace App;

use App\Events\MqttEvents\LoginEvent;
use mysql_xdevapi\Exception;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Dispatcher
{
    static private $_instance = null;

    static function getInstance()
    {
        if (self::$_instance === null) {
            $dispacher = new EventDispatcher();
            $subscriptions = require BASE_PATH . "/config/dispatcher.php"; // 订阅器配置
            foreach ($subscriptions as $s) {
                if (class_exists($s)) {
                    $dispacher->addSubscriber(new $s());   // 把订阅器注册到发布者上
                }
            }
            self::$_instance = $dispacher;
        }
        return self::$_instance;
    }

}