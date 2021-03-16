<?php
/**
 * Class LoginEvent
 * @package App\Listens\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\WebsocketListeners;

use App\Events\WebsocketEvents\LoginEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\WsMessage;

class LoginSubscript implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            LoginEvent::NAME => 'handle'
        ];
    }

    public function handle(LoginEvent $event): void
    {
        WsMessage::resSuccess($event->fd, ['hello' => 'zjh']);
    }
}