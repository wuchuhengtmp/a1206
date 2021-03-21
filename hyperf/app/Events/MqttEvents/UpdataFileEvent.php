<?php
/**
 * 上传文件
 * @package App\Events\MqttEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\MqttEvents;

class UpdataFileEvent extends BaseEvent
{
    const NAME = 'updata_file';
}