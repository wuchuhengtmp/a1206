<?php
/**
 * Class ShowDeviceDetailSubscript
 * @package App\Listens\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\ShowDeviceDetailEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
        var_dump($event->routeParams);
        var_dump("hello\n");
    }
}