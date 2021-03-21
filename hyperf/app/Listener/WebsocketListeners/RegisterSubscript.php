<?php
/**
 * 注册事件
 * @package App\Listens\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\WebsocketListeners;

use App\Events\WebsocketEvents\RegisterEvent;
use App\Model\UsersModel;
use Cake\Core\Configure\Engine\JsonConfig;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\Encrypt;
use Utils\JWT;
use Utils\WsMessage;

class RegisterSubscript implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            RegisterEvent::NAME => 'handle'
        ];
    }

    public function handle(RegisterEvent $event): void
    {
        $hasData = WsMessage::getMsgByEvent($event);
        $account = $hasData->res['data'];
        $userModel = new UsersModel($event->fd);
        $map['username'] = $account['username'];
        $map['password'] = $account['password'];
        $hasUser = $userModel->getUserByAccount($account);
        if ($hasUser->isError) {
            $userId = $userModel->createUser($account);
            $userModel->initUserConfigByUid($userId);
            $jwt = JWT::generate($userId);
            WsMessage::resSuccess($event, WsMessage::formatJWTToken($jwt));
        }
    }
}