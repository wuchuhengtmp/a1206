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
use App\Model\UsersModel;
use Utils\Message;
use Utils\ReportFormat;
use Utils\WsMessage;

class LoginValidation implements ValidationContract
{

    /**
     * @param BaseEvent $event
     * @return ReportFormat
     */
    public function goCheck(BaseEvent $event): ReportFormat
    {
        $res = new ReportFormat();
        $account = WsMessage::getMsgByEvent($event)->res['data'];
        if (!array_key_exists('username', $account) && !array_key_exists('password', $account)) {
            WsMessage::resError($event, ['errorMsg' => '请输入账号密码']);
            return $res;
        }
        if (strlen($account['password']) === 0) {
            WsMessage::resError($event, ['errorMsg' => '密码不能为空']);
            return $res;
        }
        if (strlen($account['password']) < 6) {
            WsMessage::resError($event, ['errorMsg' => '密码不能少于6位']);
            return $res;
        }
        $userModel = new UsersModel($event->fd);
        $isUser = $userModel->hasUser(['username' => $account['username']]);
        if ($isUser) {
            WsMessage::resError($event, ['errorMsg' => '用户已经存在']);
            return $res;
        }
        $res->isError = false;
        return $res;
    }
}