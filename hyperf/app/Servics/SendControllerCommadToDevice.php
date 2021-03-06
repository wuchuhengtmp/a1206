<?php
/**
 * 发送控制指令到设备
 * @package App\Servics
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Servics;

use App\CacheModel\RedisCasheModel;
use App\Events\WebsocketEvents\BaseEvent;
use App\Model\DevicesModel;
use Hyperf\Utils\ApplicationContext;
use Utils\Helper;
use Utils\MqttClient;
use Utils\WsMessage;

class SendControllerCommadToDevice
{
    function send(BaseEvent $event, $content = null, int $msgid)
    {
        $msgid = (string) $msgid;
        $deviceId = (int) $event->routeParams['id'];
        $device = (new DevicesModel())->getOneById($deviceId);
        $message = (function () use($device, $deviceId, $content, $msgid) {
            $c = [
                'type' => 'JRBJQ_AIR724',
                'deviceid' => $device['device_id'],
                'msgid' => $msgid,
                'command' => 'play_crtl',
                'content' => $content
            ];
            return Helper::fMqttMsg($c);
        })();
        $data = WsMessage::getMsgByEvent($event)->res;
        $data['message'] = $message;
        $topic = Helper::formatTopicByDeviceId($device['device_id']);
        (new MqttClient())->getClient()->publish($topic, $message, 1);
        $redisModel = ApplicationContext::getContainer()->get(RedisCasheModel::class);
        $redisModel->setControMessage($device['device_id'], (int) $msgid,json_encode($data));
    }
}