<?php
/**
 * 订阅删除
 * @package App\Listens\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\DestroyDeviceFileEvent;
use App\Model\DeviceFilesModel;
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
        $deviceId = (int) $event->routeParams['id'];
        $fileId = (int) $event->routeParams['fileId'];
        // todo 这里要通知设备删除文件，成功后再删除文件
        $isOk = (new DeviceFilesModel($event->fd))->destroy($deviceId, $fileId);
        WsMessage::resSuccess($event);
    }
}