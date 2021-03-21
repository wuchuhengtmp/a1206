<?php
/**
 * 上传设备文件事件
 * @package App\Events\WebsocketEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\WebsocketEvents;

class UploadDeviceFileEvent extends BaseEvent
{
    const NAME = 'ws.post.api./me/devices/:id/files';
}