<?php
/**
 * 发送新增文件指令到设备
 * @package App\Servics
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Servics;

use App\CacheModel\RedisCasheModel;
use App\Events\WebsocketEvents\BaseEvent;
use App\Exception\WsExceptions\BackEndException;
use App\Exception\WsExceptions\BaseException;
use App\Model\DevicesModel;
use Hyperf\Utils\ApplicationContext;
use Utils\Helper;
use Utils\MqttClient;
use Utils\WsMessage;

class SendCreateFileCommandToDevice extends BaseAbstract
{

    /**
     * @param BaseEvent $event
     * @param null $content
     * @param null $fileId 文件id
     * @throws \App\Exception\WsExceptions\ConnectBrokenException
     */
    public function send(BaseEvent $event, $content = null, $fileId = null, $name = null): void
    {
        $msgid = WsMessage::getMsgByEvent($event)->res['msgid'];
        $deviceId = (int) $event->routeParams['id'];
        $device = (new DevicesModel())->getOneById($deviceId);
        $message = (function () use($device, $deviceId, $content, $msgid) {
            $c = [
                'type' => 'JRBJQ_AIR724',
                'deviceid' => $device['device_id'],
                'msgid' => $msgid,
                'command' => 'updata_file',
                'content' => $content
            ];
            return Helper::fMqttMsg($c);
        })();
        $data = WsMessage::getMsgByEvent($event)->res;
        if ($fileId !== null) {$data['fileId'] = $fileId ;}
        if ($name !== null) {$data['name'] = $name ;}
        $data['message'] = $message;
        $topic = Helper::formatTopicByDeviceId($device['device_id']);


        $redisModel = ApplicationContext::getContainer()->get(RedisCasheModel::class);
        $event = new BaseEvent($event->fd, $event->method, $event->url);
        $e = new BaseException('');
        $e->url = $event->url;
        $e->method = $event->method;
        // 设备不在线
        if (!$redisModel->isDeviceOnlineByDeviceId($device['device_id'])) {
            $redisModel->addUploadFileQueue($topic, $message, $device, $msgid, $data);
            $e->errorMsg = '设备不在线, 已加入队列中';
            throw $e;
        }
        // 设备在下载别的文件，没空,后面再下
        if (!$redisModel->isDeviceFreeByDeviceId($device['device_id'])) {
            $redisModel->addUploadFileQueue($topic, $message, $device, $msgid, $data);
            $e->errorMsg = '设备正在下载别的文件, 已加入队列中';
            throw $e;
        }
        // 标记为非空闲状态，通知设备下载文件
        $redisModel->tagDeviceQueueToBusy($device['device_id']);
        (new MqttClient())->getClient()->publish($topic, $message, 1);
        $redisModel->setLastFileSendTimeByDeviceId($device['device_id']);

        $redisModel->setControMessage($device['device_id'], (int) $msgid, \json_encode($data));
    }

    /**
     * 发送队列的文件
     * @param $topic
     * @param $message
     * @param $device
     * @param $msgid
     * @param $data
     */
    public function sendFileFormQueueByDeviceId(string $deviceId): void
    {
        $redisModel = ApplicationContext::getContainer()->get(RedisCasheModel::class);
        $queue = $redisModel->getUploadFileQueueByDeviceId($deviceId);
        $data = array_shift($queue);
        $topic = $data['topic'];
        $message = $data['message'];
        $device = $data['device'];
        $msgid = $data['msgid'];
        $data = $data['data'];

        $redisModel->setLastFileSendTimeByDeviceId($device['device_id']);
        // 标记为非空闲状态，通知设备下载文件
        $redisModel->tagDeviceQueueToBusy($deviceId);
        (new MqttClient())->getClient()->publish($topic, $message, 1);
        $redisModel = ApplicationContext::getContainer()->get(RedisCasheModel::class);
        $redisModel->setControMessage($device['device_id'], (int) $msgid, \json_encode($data));
    }
}