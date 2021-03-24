<?php
/**
 * 播放下一曲
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\CacheModel\RedisCasheModel;
use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\PlayFilesEvent;
use App\Servics\SendControllerCommadToDevice;
use App\Servics\SendQueryCommandToDevice;
use Hyperf\Utils\ApplicationContext;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\WsMessage;

class PlayFilesSubscript implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            PlayFilesEvent::NAME => 'handle'
        ];
    }

    /**
     * @param BaseEvent $event
     */
    public function handle(BaseEvent $event):void
    {
        $status = $event->routeParams['status'];
        $content = ['play_file' => (int) $status];
        (new SendControllerCommadToDevice())->send($event, $content);
    }
}