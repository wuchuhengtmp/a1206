<?php
/**
 * 添加定时请求验证
 * @package App\Validations\WsValidations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

use App\Events\WebsocketEvents\BaseEvent;

class AddConfigTimeRequestValidation extends BaseValidation
{
    public function getRules(): array
    {
         return [
             'type_time' => [
                 'required',
                 'in:0000000,1111111,1100000,0000011'
             ],
             'stime' => [
                 'required',
                 'timestampCheck'
             ],
             'etime' => [
                 'required',
                 'timestampCheck'
             ]
         ];
    }

    public function timestampCheck(BaseEvent $event, $data, $fieldName, $callBack)
    {
        $isOk = strlen($data[$fieldName]) === 10;
        !$isOk && $callBack($fieldName . ' 不是正确的10位时间戳');
    }
}