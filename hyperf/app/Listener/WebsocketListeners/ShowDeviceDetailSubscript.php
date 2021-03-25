<?php
/**
 * Class ShowDeviceDetailSubscript
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\ShowDeviceDetailEvent;
use App\Model\DevicesModel;
use App\Servics\SendQueryCommandToDevice;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\JWT;
use Utils\WsMessage;

class ShowDeviceDetailSubscript implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ShowDeviceDetailEvent::NAME => 'handle'
        ];
    }

    /**j
     * 设备详情
     * @param BaseEvent $event
     */
    public function handle(BaseEvent $event): void
    {
        (new SendQueryCommandToDevice())->send($event);
    }
}