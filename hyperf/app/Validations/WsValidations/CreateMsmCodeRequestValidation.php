<?php
/**
 * Class UploadFileValidation
 * @package App\Validations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

use App\Events\WebsocketEvents\BaseEvent;
use App\Model\UsersModel;
use App\Validations\WsValidations\BaseValidation;
use Hyperf\Utils\ApplicationContext;

class CreateMsmCodeRequestValidation extends BaseValidation
{
    public function getRules(): array
    {
        return [
            'phone' => ['required', 'checkPhone'],
        ];
    }

    public function getMessages(): array
    {
        return [
            'phone.required' => '手机号不能为空',
            'phone.checkPhone' => '手机格式不正确'
        ];
    }

    public function checkPhone(BaseEvent $event, $data, $fieldName, $callBack)
    {
        if (array_key_exists('phone',  $data)) {
            $reg = '/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}$/';
            $phone = $data[$fieldName];
            if (!preg_match($reg, $phone)) {
                    $callBack('手机号不正确');
            }
            $user = UsersModel::query()->where('username', $phone)->first();
            !$user && $callBack('没有这个手机号');
        }
    }
}