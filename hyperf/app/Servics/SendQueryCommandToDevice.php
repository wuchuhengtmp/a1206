<?php
/**
 * 查询设备参数
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

class SendQueryCommandToDevice extends BaseAbstract
{
    public function send(BaseEvent $event, $content = null): void
    {
        $deviceId = (int) $event->routeParams['id'];
        $device = (new DevicesModel())->getOneById($deviceId);
        $msgid = (int) WsMessage::getMsgByEvent($event)->res['msgid'];
        $message = (function () use($device, $deviceId, $content, $msgid) {
            $c = [
                'type' => 'JRBJQ_AIR724',
                'deviceid' => $device['device_id'],
                'msgid' => (string) $msgid,
                'command' => 'get_data_all',
                'content' => $content
            ];
            return Helper::fMqttMsg($c);
        })();
        $topic = Helper::formatTopicByDeviceId($device['device_id']);
        (new MqttClient())->getClient()->publish($topic, $message, 1);
        $redisModel = ApplicationContext::getContainer()->get(RedisCasheModel::class);
        $redisModel->setControMessage($device['device_id'], $msgid, $message);
    }
}