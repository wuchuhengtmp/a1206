<?php
/**
 * Class UploadFileValidation
 * @package App\Validations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

use App\CacheModel\RedisCasheModel;
use App\Events\WebsocketEvents\BaseEvent;
use App\Model\UsersModel;
use App\Validations\WsValidations\BaseValidation;
use Hyperf\Utils\ApplicationContext;

class ResetPasswordRequestValidation extends BaseValidation
{
    public function getRules(): array
    {
        return [
            'code' => ['required'],
            'access_key' => ['required', 'checkAccessKey'],
            'password' => ['required', 'checkPassword']
        ];
    }

    public function getMessages(): array
    {
        return [
            'code.required' => '验证码不能为空',
            'password.required' => '密码不能为空',
        ];
    }

    public function checkAccessKey(BaseEvent $event, $data, $fieldName, $callBack)
    {
        if (array_key_exists($fieldName,  $data)) {
            $key = $data[$fieldName];
            $redisModel = ApplicationContext::getContainer()->get(RedisCasheModel::class);
            !$redisModel->hasMsgInfoByKey($key) && $callBack('短信签名无效');
            $codeInfo = $redisModel->getMsgInfo($key);
            if ($codeInfo['code'] !== $data['code']) {
                $callBack('验证码不正确');
            }
        }
    }

    /**
     * 密码验证
     * @param BaseEvent $event
     * @param $data
     * @param $fieldName
     * @param $callBack
     */
    public function checkPassword(BaseEvent $event, $data, $fieldName, $callBack)
    {
        if (array_key_exists($fieldName, $data)) {
            if (strlen($data[$fieldName]) < 8 )  {
                $callBack('密码不能小于8位');
            }
        }
    }
}