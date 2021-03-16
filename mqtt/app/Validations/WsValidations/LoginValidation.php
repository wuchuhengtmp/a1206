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
use Utils\ReportFormat;
use Utils\WsMessage;

class LoginValidation extends BaseValidation implements ValidationContract
{

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [
            'username' => [ 'required' ],
            'password' => [ 'required' ]
        ];
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return [
            'username.required' => '账号不能为空',
            'password.required' => '密码不能为空'
        ];
    }
}