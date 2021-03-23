<?php
/**
 * Class DevicePlaySubscript
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\DevicePlayDevent;
use App\Model\DevicesModel;
use App\Model\FilesModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\Context;
use Utils\Helper;
use Utils\MqttClient;
use Utils\WsMessage;

class DevicePlaySubscript implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [DevicePlayDevent::NAME => 'handle'];
    }

    public function handle(BaseEvent $event): void
    {
        $play_status = WsMessage::getMsgByEvent($event)->res['data']['play_status'];
        $deviceId = (int) $event->routeParams['id'];
        $device = (new DevicesModel())->getOneById($deviceId);
        $message = (function () use($device, $deviceId, $play_status) {
            $c = [
                'type' => 'JRBJQ_AIR724',
                'deviceid' => $deviceId,
                'msgid' => $device['device_id'] . time(),
                'command' => 'play_crtl',
                'content' => [
                    'play_state' => (int) $play_status
                ]
            ];
            return Helper::fMqttMsg($c);
        })();
        $topic = Helper::formatTopicByDeviceId($device['device_id']);
        (new MqttClient())->getClient()->publish($topic, $message, 1);
    }
}