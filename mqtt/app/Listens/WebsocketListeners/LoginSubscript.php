<?php
/**
 * Class LoginEvent
 * @package App\Listens\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\WebsocketListeners;

use App\Events\WebsocketEvents\LoginEvent;
use App\Model\UsersModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\JWT;
use Utils\Message;
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
        $hasData = WsMessage::getMsgByEvent($event);
        if (!$hasData->isError) {
            $msg = $hasData->res;
            $account = $msg['data'];
            $userModel = new UsersModel($event->fd);
            $user = $userModel->getUserByAccount($account)->res;
            $jwt = JWT::generate((int) $user['id']);
            WsMessage::resSuccess($event, WsMessage::formatJWTToken($jwt)); }
    }
}