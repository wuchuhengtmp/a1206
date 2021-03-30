<?php
/**
 * 播放模式
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\PlayModeEvent;
use App\Servics\SendControllerCommadToDevice;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\WsMessage;

class PlayModeSubscript implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [PlayModeEvent::NAME => 'handle'];
    }

    public function handle(BaseEvent  $event)
    {
         $play_status = WsMessage::getMsgByEvent($event)->res['data']['play_mode'];
         $msgid = WsMessage::getMsgByEvent($event)->res['msgid'];
        (new SendControllerCommadToDevice())->send($event, [
            'play_mode' => (int) $play_status
        ], (int) $msgid);
    }
}