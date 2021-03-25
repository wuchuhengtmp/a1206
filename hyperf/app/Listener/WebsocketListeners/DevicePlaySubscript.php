<?php
/**
 * Class DevicePlaySubscript
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\CacheModel\RedisCasheModel;
use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\DevicePlayDevent;
use App\Model\DevicesModel;
use App\Model\FilesModel;
use App\Servics\SendControllerCommadToDevice;
use Hyperf\Utils\ApplicationContext;
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
        $mgsid = WsMessage::getMsgByEvent($event)->res['msgid'];
        (new SendControllerCommadToDevice())->send($event,[ 'play_state' => (int) $play_status ], (int) $mgsid);
    }
}