<?php
/**
 * 硬盘配置
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

return [
    'default' => 'local',
    'local' => [
        'host' => 'http://127.0.0.1:' . env('HTTP_PORT'),
        'driver' => \App\Storages\LocalStorage::class,
        'prefixDir' => 'files/localDisk', //url可访开始目
        'root' => "storages"  // 文件存放开始目录
    ]
];
