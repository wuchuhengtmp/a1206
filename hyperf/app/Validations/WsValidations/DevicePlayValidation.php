<?php
/**
 * Class DevicePlayValidation
 * @package App\Validations\WsValidations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

use _HumbugBoxa9bfddcdef37\Nette\Schema\ValidationException;

class DevicePlayValidation extends BaseValidation
{
    public function getRules(): array
    {
        return [
            'play_status' => [
                'in:0,1,2'
            ]
        ];
    }

    public function getMessages(): array
    {
        return [
            'play_status.in' => '播放状态必须为0, 1, 2'
        ];
    }
}