<?php
/**
 * 监听所有事件
 * @package App\Listens\MqttListens
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\MqttListens;

use App\Dispatcher;
use App\Events\MqttEvents\LoggedEvent;
use App\Events\MqttEvents\RegisterEvent;
use Simps\DB\BaseModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Utils\Context;

class LogSubscript extends BaseModel implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        $dir = dirname((new \ReflectionClass(LoggedEvent::class))->getFileName());
        $files = scandir($dir);
        $files = preg_grep ('/^.*Event\.php/i', $files);
        $resData = [];
        foreach ($files as &$file) {
            $file = str_replace('.php', '', $file);
            $classFile  = "App\\Events\\MqttEvents\\" . $file;
            $resData[$classFile::NAME] = 'handle';
        }
        return $resData;
    }

    public function handle($event):void
    {
        var_dump(date("H:i:s", time()) . " 触发事件:" . $event::NAME );
    }
}