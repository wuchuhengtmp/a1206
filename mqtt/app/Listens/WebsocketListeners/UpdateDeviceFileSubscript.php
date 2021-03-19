<?php
/**
 * Class UpdateDeviceFileSubscript
 * @package App\Listens\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\UpdateDeviceFileEvent;
use App\Events\WebsocketEvents\UploadDeviceFileEvent;
use App\GlobalChannel;
use App\Model\DeviceFilesModel;
use App\Model\DevicesModel;
use App\Model\FilesModel;
use FastRoute\DataGenerator;
use Simps\Client\MQTTClient;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\WsMessage;

class UpdateDeviceFileSubscript implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
             UpdateDeviceFileEvent::NAME => 'handle'
        ];
    }

    /**
     * @param BaseEvent $event
     */
    public function handle(BaseEvent  $event): void
    {
        $data = WsMessage::getMsgByEvent($event)->res['data'];
        // 过滤重复数据
        $data = $this->filterData($event, $data);
        $user = $event->getAuth()->res;
        $deviceId = (int) $event->routeParams['id'];
        // 新添加ids
        $selectIds = [];
        // 删除ids
        $unSelectIds = [];
        foreach ($data as $e) {
            $isExists = (new DeviceFilesModel($event->fd))->hasOne(['device_id' => $deviceId, 'file_id' => $e['id']]);
            // 新文件
            if ($isExists) {
                $unSelectIds[] = $e['id'];
            } else {
                $selectIds[] = $e['id'];
            }
        }
        // 添加
        foreach ($selectIds as $fileId ) {
            $this->sendFileToMqttByFileId($event, (int) $fileId);
        }
    }

    /**
     *  过滤冗余数据
     * @param array $data
     * @return array]
     */
    public function filterData(BaseEvent $event, array $data): array
    {
        $dModel = new DeviceFilesModel($event->fd);
        $deviceId = (int) $event->routeParams['id'];
        foreach ($data as $k => &$e) {
            $isData = $dModel->hasOne(['file_id' => $e['id'], 'device_id' => $deviceId]);
            if ($isData && $e['isSelect'] ) {
                unset($data[$k]);
            } else if (!$isData && !$e['isSelect']) {
                unset($data[$k]);
            }
        }
        return $data;
    }

    /**
     * @param int $fileId
     */
    public function sendFileToMqttByFileId(BaseEvent $event, int $fileId): void
    {
        $fileModel = new FilesModel($event->fd);
        $deviceId = (int) $event->routeParams['id'];
        $device = (new DevicesModel($event->fd))->getOneById($deviceId);
        $config = config('mqttClient');
        $client = new MQTTClient($config);
        $file = $fileModel->getFileById($fileId);
        $size = 0;
        if ($file['size'] === null) {
            $size = strlen(file_get_contents($file['url']));
            $fileModel->updateSize((int)$file['id'], $size);
        }
        $content = (function () use($device, $file, $size) {
            $c = [
                'type' => 'JRBJQ_AIR724',
                'deviceid' => '',
                'msgid' => $device['device_id'] . time(),
                'command' => 'updata_file',
                'updata_file' => [
                    'op_mode' => 1,
                    'http_root' => $file['url'],
                    'file_check_sum' => $size,
                    'del_file' => -1
                ]
            ];
            $c = json_encode($c);
            $c = sprintf("%04dXCWL%s", strlen($c), $c);
            return $c;
        })();
        $will = [
            'topic' => env('MQTT_TOPIC_PREFIX') . $device['device_id'],
            'qos' => 1,
            'retain' => 0,
            'content' => $content,
        ];
        GlobalChannel::getInstance()->push($will);
    }
}