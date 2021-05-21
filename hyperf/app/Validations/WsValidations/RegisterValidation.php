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
use App\Exception\WsExceptions\UserException;
use App\Model\UsersModel;
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
        $e = '';
        $account = WsMessage::getMsgByEvent($event)->res['data'];
        if (!array_key_exists('username', $account)) {
            $e = new UserException('请输入账号');
        }
        if (!array_key_exists('password', $account)) {
            $e = new UserException('请输入密码');
        }
        if (strlen($account['password']) === 0) {
            $e = new UserException('密码不能为空');
        }
        if (strlen($account['password']) < 6) {
            $e = new UserException('密码不能少于6位');
        }
        $userModel = new UsersModel();
        $isUser = $userModel->hasUser(['username' => $account['username']]);
        if ($isUser) {
            $e = new UserException('用户已经存在');
        }
        if ($e !== '') {
            $e->url = $event->url;
            $e->method = $event->method;
            throw $e;
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