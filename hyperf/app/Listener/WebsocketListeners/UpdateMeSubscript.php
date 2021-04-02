<?php
/**
 * 更新用户信息
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\UpdateMeEnvent;
use App\Model\UsersModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\WsMessage;

class UpdateMeSubscript implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            UpdateMeEnvent::NAME => 'handle'
        ];
    }

    public function handle(BaseEvent  $event): void
    {
        $me = $event->getAuth()->res;
        $me = UsersModel::where("id", $me["id"])->first();
        $data = WsMessage::getMsgByEvent($event)->res['data'];
        $me->nickname = $data['nickname'];
        $me->avatar = $data['avatar'];
        $isOk = $me->save();
        $isOk ? WsMessage::resSuccess($event, $me->toArray()) : WsMessage::resError($event);
    }
}