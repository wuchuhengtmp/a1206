<?php
/**
 * 监听所有事件
 * @package App\Listens\MqttListens
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\MqttListens;

use App\Dispatcher;
use App\Events\MqttEvents\BaseEvent;
use App\Events\MqttEvents\LoggedEvent;
use App\Events\MqttEvents\RegisterEvent;
use Simps\DB\BaseModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Utils\Context;
use Utils\Helper;
use Utils\Message;
use Codedungeon\PHPCliColors\Color;

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
        $skipClass = [
            BaseEvent::class
        ];
        foreach ($files as &$file) {
            $file = str_replace('.php', '', $file);
            $classFile  = "App\\Events\\MqttEvents\\" . $file;
             if (!in_array($classFile, $skipClass)) {
                 $resData[$classFile::NAME] = 'handle';
             }
        }
        return $resData;
    }

    public function handle($event):void
    {
        $hasConnect = Message::getConnectMsg($event->fd);
        $event_name = Helper::sprinstfLen($event::NAME, 15);
        $msg = date("[H:i:s]", time()) . " trigger event:" . Color::GREEN . $event_name . Color::RESET;
        if (!$hasConnect->isError) {
            $msg = sprintf(
                $msg . " connected clientId: %s",
                Color::YELLOW . $hasConnect->res['client_id'] . Color::RESET
            );
        }
        echo $msg . PHP_EOL;
    }
}