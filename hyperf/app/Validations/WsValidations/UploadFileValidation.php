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

class UploadFileValidation extends BaseValidation
{
    public function getRules(): array
    {
        return [
            'name' => ['required'],
            'file' => ['required']
        ];
    }

    public function getMessages(): array
    {
        return [
            'name.required' => '文件名不能为空',
            'file.required' => '文件不能为空'
        ];
    }
}