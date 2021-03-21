<?php
/**
 * 登录验证
 * @package App\Validations\WsValidations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

use App\Contracts\ValidationContract;
use App\Events\WebsocketEvents\BaseEvent;
use App\Exceptions\WsExceptions\UserException;
use App\Model\UsersModel;
use Utils\Message;
use Utils\ReportFormat;
use Utils\WsMessage;

class RegisterValidation implements ValidationContract
{

    /**
     * @param BaseEvent $event
     * @return ReportFormat
     */
    public function goCheck(BaseEvent $event): void
    {
        $res = new ReportFormat();
        $account = WsMessage::getMsgByEvent($event)->res['data'];
        if (!array_key_exists('username', $account) && !array_key_exists('password', $account)) {
            throw new UserException('请输入账号密码');
        }
        if (strlen($account['password']) === 0) {
            throw new UserException('密码不能为空');
        }
        if (strlen($account['password']) < 6) {
            throw new UserException('密码不能少于6位');
        }
        $userModel = new UsersModel($event->fd);
        $isUser = $userModel->hasUser(['username' => $account['username']]);
        if ($isUser) {
            throw new UserException('用户已经存在');
        }
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return [];
    }
}