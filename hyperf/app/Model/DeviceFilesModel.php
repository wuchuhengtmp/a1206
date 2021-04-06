<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use PHPStan\File\FileMonitor;

/**
 */
class DeviceFilesModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'device_files';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
        'device_id'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     *  更新设备文件
     * @param $curdInfo
     * @param $deviceId
     */
    public function updateDeviceFile($curdInfo, $deviceId): void
    {
        $devices = DevicesModel::query()->where('device_id', $deviceId)->get();
        $device = $devices->first();
        foreach ($curdInfo as $e) {
            $files = self::query()->where('file_id', $e['id'])->where('device_id', $device->id)->get();
            //添加
            if ($e['isSelect']) {
                if ($files->isEmpty()) {
                    $f = new self();
                    $f->file_id = $e['id'];
                    $f->device_id = $device->id;
                    $f->save();
                }
            } else {
                // 删除
                if ($files->isNotEmpty()) {
                    $files->first()->delete();
                }
            }
        }
    }
}