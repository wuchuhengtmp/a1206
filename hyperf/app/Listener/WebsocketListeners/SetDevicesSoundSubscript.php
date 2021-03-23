<?php
/**
 * Class SetDevicesSoundSubscript
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\SetDeviceSoundEvent;
use App\Model\DevicesModel;
use App\Servics\SendControllerCommadToDevice;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\Helper;
use Utils\MqttClient;
use Utils\WsMessage;

class SetDevicesSoundSubscript implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [SetDeviceSoundEvent::NAME => 'handle'];
    }

    public function handle(BaseEvent $event): void
    {
        $play_sound = WsMessage::getMsgByEvent($event)->res['data']['play_sound'];
        $content = ['play_sound' => $play_sound];
        (new SendControllerCommadToDevice())->send($event, $content);
    }
}