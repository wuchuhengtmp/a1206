<?php
/**
 * 订阅展示设备文件
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\ShowDevicefilesEvent;
use App\Model\DeviceFilesModel;
use App\Model\DevicesModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\WsMessage;

class ShowDeviceFilesSubscript implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ShowDevicefilesEvent::NAME => 'handle'
        ];
    }

    public function handle(BaseEvent  $event): void
    {
        $id = (int) $event->routeParams['id'];
        $files = (new DevicesModel())->getFilesByDeviceId($id);
        WsMessage::resSuccess($event, $files);
    }
}