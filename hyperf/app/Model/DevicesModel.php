<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Db;
use Hyperf\DbConnection\Model\Model;
/**
 */
class DevicesModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'devices';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function getDevicesByUid(int $uid): array
    {
        return self::query()->where('user_id', $uid)->get()->toArray();
    }

    public function getFilesByDeviceId(int $deviceId): array
    {
        $files = Db::select("SELECT
                f.*,
                df.id as deviceId
            FROM
                device_files as df
                INNER JOIN files as f ON f.id = df.file_id
            WHERE
                df.device_id = $deviceId");

        $fileIds = array_column($files, 'id');
        $defaultFiles = (new ConfigsModel())->getDefaultAudio();
        foreach ($defaultFiles as &$dFile) {
            $dFile['isSelect'] = in_array($dFile['file_id'], $fileIds);
            $dFile['id'] = $dFile['file_id'];
            unset($dFile['file_id']);
        }
        return $defaultFiles;
    }
}