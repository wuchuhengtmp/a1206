<?php
/**
 * 播放模式验证
 * @package App\Validations\WsValidations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

use App\Events\WebsocketEvents\BaseEvent;

class PlayModeValidation extends BaseValidation
{
    public function getRules(): array
    {
        return [
            'play_mode' => [
                'required',
                'in:0,1,2,3,4,5,6,7'
            ]
        ];
    }
}