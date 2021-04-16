<?php
/**
 * Class UpdateMeRequestValidation
 * @package App\Validations\WsValidations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

class UpdateMeRequestValidation extends BaseValidation
{
    public function getRules(): array
    {
        return [
            'avatar' => ['required'],
            'nickname' => ['required'],
//            'lng' => ['required'],
//            'lat' => ['required'],
        ];
    }
}