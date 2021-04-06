<?php
/**
 * 关于我们
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\Events\WebsocketEvents\AboutEvent;
use App\Events\WebsocketEvents\BaseEvent;
use App\Model\ConfigsModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\WsMessage;

class AboutSubscript implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            AboutEvent::NAME => 'handle'
        ];
    }

    public function handle(BaseEvent $event): void
    {
        $value = ConfigsModel::where('name', 'ABOUT')->first()->value;
         WsMessage::resSuccess($event, ['content' => $value]);
    }
}