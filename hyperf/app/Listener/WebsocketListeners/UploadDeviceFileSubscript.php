<?php
/**
 * Class UploadSubscript
 * @package App\Listens\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\UploadDeviceFileEvent;
use App\Model\FilesModel;
use App\Model\DeviceFilesModel;
use App\Model\UsersModel;
use App\Storages\Storage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\WsMessage;

class UploadDeviceFileSubscript implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            UploadDeviceFileEvent::NAME => 'handle'
        ];
    }

    public function handle(BaseEvent  $event)
    {
        $msg = WsMessage::getMsgByEvent($event)->res;
        $name = $msg['data']['name'];
        $fileBase64 = $msg['data']['file'];
        $fileCon = base64_decode($fileBase64);
        $disk = config('filesystems')['default'];
        $diskInstance = (new Storage())->disk($disk);
        $path = $diskInstance->put(sprintf("%s/%s.mp3", date('Y-m-d', time()), $name), $fileCon);
        $fId = (new FilesModel($event->fd))->createOne($path, $disk);
        $me = $event->getAuth()->res;
        $deviceId = (int) $event->routeParams['id'];
        // 把文件添加到设备中
        $dfid = (new DeviceFilesModel($event->fd))->createDeviceFile($deviceId, $fId);
        // todo 这里要把新的上传的文件报告给设备
        WsMessage::resSuccess($event, [
            'id' => $dfid,
            'name' => $name,
            'url' => $diskInstance->url($path)
        ]);
    }
}