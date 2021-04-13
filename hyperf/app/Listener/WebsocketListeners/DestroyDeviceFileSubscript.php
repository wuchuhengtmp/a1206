<?php
/**
 * 订阅删除
 * @package App\Listen\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\DestroyDeviceFileEvent;
use App\Model\DeviceFilesModel;
use App\Model\FilesModel;
use App\Servics\SendCreateFileCommandToDevice;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\WsMessage;

class DestroyDeviceFileSubscript implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            DestroyDeviceFileEvent::NAME => 'handle'
        ];
    }

    /**
     * @param BaseEvent $event
     */
    public function handle(BaseEvent $event): void
    {

        $fileId = $event->routeParams['fileId'];
        $dFile = DeviceFilesModel::where('id', $fileId)->first();
        $fileInfo = pathinfo($dFile->file->path);
        $content = [
            'op_mode' => 2,
            'del_file' => $fileInfo['basename']
        ];
        (new SendCreateFileCommandToDevice())->send($event, $content, $fileId);
    }
}