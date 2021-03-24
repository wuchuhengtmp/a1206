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
         $triggerModes = WsMessage::getMsgByEvent($event)->res['data'];
        (new SendControllerCommadToDevice())->send($event, [
            'trigger_modes' => $triggerModes
        ]);
    }
}