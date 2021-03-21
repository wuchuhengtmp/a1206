<?php
/**
 * ws 日志打印
 * @package App\Listens\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use Codedungeon\PHPCliColors\Color;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\Helper;
use Utils\Message;

class LogSubscript implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        $dir = dirname((new \ReflectionClass(BaseEvent::class))->getFileName());
        $files = scandir($dir);
        $files = preg_grep ('/^.*Event\.php/i', $files);
        $resData = [];
        $skipClass = [
            BaseEvent::class
        ];
        foreach ($files as &$file) {
            $file = str_replace('.php', '', $file);
            $classFile  = "App\\Events\\WebsocketEvents\\" . $file;
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
//        if (!$hasConnect->isError) {
//            $msg = sprintf(
//                $msg . " connected clientId: %s",
//                Color::YELLOW . $hasConnect->res['client_id'] . Color::RESET
//            );
//        } else if (!Message::getDisconnectClientId()->isError) {
//            $msg = sprintf(
//                $msg . " connected clientId: %s",
//                Color::RED . Message::getDisconnectClientId()->res . Color::RESET
//            );
//        }
        echo $msg . PHP_EOL;
    }

}