<?php
/**
 * 更新设备属性验证
 * @package App\Validations\WsValidations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

use App\Model\CategoriesModel;

class UpdateDevicePropertyRequestValidation extends BaseValidation
{
    public function getRules(): array
    {
        return [
            'category_id' => ['required', 'exists:' . CategoriesModel::class . ",id"],
            'alias' => ['required']
        ];
    }
}