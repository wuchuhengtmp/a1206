<?php
/**
 * Class LimitSoundValidation
 * @package App\Validations\WsValidations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

class LimitSoundValidation extends BaseValidation
{
    public function getRules(): array
    {
        return [
             'play_sound' => [
                 'required',
                 'between:0,30'
             ]
        ];
    }
}