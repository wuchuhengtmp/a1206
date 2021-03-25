<?php
/**
 * Class MsgIdMustBeExistsValidation
 * @package App\Validations\WsValidations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

class MsgIdMustBeExistsValidation extends BaseValidation
{
    public function getRules(): array
    {
        return [
            'msgid' => ['required']
        ];
    }
}