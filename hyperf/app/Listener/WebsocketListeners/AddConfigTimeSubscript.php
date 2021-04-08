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
use App\Servics\SendCreateConfigTimeCommadToDevice;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\WsMessage;

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
        $data = WsMessage::getMsgByEvent($event)->res['data'];
        $msgid = WsMessage::getMsgByEvent($event)->res['msgid'];
        $content['type_time'] = (int) $data['type_time'];
        $content['stime'] = (int) $data['stime'];
        $content['etime'] = (int) $data['etime'];
        $content['ctime'] = time();
        (new SendCreateConfigTimeCommadToDevice())->send($event,$content, (int) $msgid);
    }
}