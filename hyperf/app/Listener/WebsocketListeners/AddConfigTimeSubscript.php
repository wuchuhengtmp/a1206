<?php
/**
 * 添加定时订阅
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\Events\WebsocketEvents\AddConfigTimeEvent;
use App\Events\WebsocketEvents\BaseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddConfigTimeSubscript implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            AddConfigTimeEvent::NAME => 'handle'
        ];
    }

    public function handle(BaseEvent  $event)
    {
        var_dump("hello\n". __FUNCTION__);
    }
}