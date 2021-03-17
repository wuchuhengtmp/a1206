<?php
/**
 * Class PingSubscript
 * @package App\Listens\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\PingEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\WsMessage;

class PingSubscript implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            PingEvent::NAME => 'handle'
        ];
    }

    public function handle(BaseEvent $event): void
    {
        WsMessage::resSuccess($event);
    }
}