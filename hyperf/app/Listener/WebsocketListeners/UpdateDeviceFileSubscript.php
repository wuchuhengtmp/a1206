<?php
/**
 * Class UpdateDeviceFileSubscript
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\CacheModel\RedisCasheModel;
use App\Events\WebsocketEvents\BaseEvent;
use App\Servics\SendCreateFileCommandToDevice;
use Hyperf\Utils\ApplicationContext;
use App\Events\WebsocketEvents\UpdateDeviceFileEvent;
use App\Events\WebsocketEvents\UploadDeviceFileEvent;
use App\GlobalChannel;
use App\Model\DeviceFilesModel;
use App\Model\DevicesModel;
use App\Model\FilesModel;
use FastRoute\DataGenerator;
use parallel\Runtime\Error\IllegalVariable;
use Simps\Client\MQTTClient;
use Swoole\Coroutine\Redis;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\Helper;
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
        $deviceId = (int) $event->routeParams['id'];
        // 新添加ids
        $selectIds = [];
        // 删除ids
        $unSelectIds = [];
        foreach ($data as $e) {
            $isExists = (bool) (new DeviceFilesModel())->where('device_id',  $deviceId)
                ->where('file_id', $e['id'])
                ->count();
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
//            $fileModel = (new DeviceFilesModel());
//            $fileModel->file_id = $fileId;
//            $fileModel->device_id = $deviceId;
//            $fileModel->save();
        }
        // 要删除的文件
        foreach ($unSelectIds as $fileId ) {
            // todo: 这里需要写删除设备的业务代码
            $this->_delDeviceFile($event, (int) $fileId);
        }
    }

    /**
     *  过滤冗余数据
     * @param array $data
     * @return array]
     */
    public function filterData(BaseEvent $event, array $data): array
    {
        $deviceId = (int) $event->routeParams['id'];
        foreach ($data as $k => &$e) {
            $isData = DeviceFilesModel::query()->where('file_id', $e['id'])
                ->where('device_id', $deviceId)
                ->first();
            if ($isData && $e['isSelect'] ) {
                unset($data[$k]);
            } else if (!$isData && !$e['isSelect']) {
                unset($data[$k]);
            }
        }
        return $data;
    }

    /**
     * 新增文件
     */
    public function sendFileToMqttByFileId(BaseEvent $event, int $fileId): void
    {
        $fileModel = new FilesModel();
        $file = $fileModel->where('id', $fileId)->first();
        $content = [
            'op_mode' => 1,
            'http_root' => $file['url'],
            'file_check_sum' => $file->size,
            'file_lenth' => $file->size,
            'del_file' => -1
        ];
        (new SendCreateFileCommandToDevice())->send($event, $content);
    }

    /**
     *  删除设备文件
     * @param BaseEvent $event
     * @param int $fileId
     */
    private function _deldeviceFile(BaseEvent $event, int $fileId): void
    {
        $file = FilesModel::where('id', $fileId)->first();
        $fileInfo = pathinfo($file->path);
        $content = [
            'op_mode' => 2,
            'del_file' => $fileInfo['basename']
        ];
        (new SendCreateFileCommandToDevice())->send($event, $content);
    }
}