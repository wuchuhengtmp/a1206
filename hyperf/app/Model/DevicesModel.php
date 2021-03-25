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
    protected $fillable = [
        'device_id',
        'user_id',
        'ip_address',
        'keepalive',
        'protocol',
        'status',
        'vender',
        'version',
        'last_ack_at',
        'created_at',
        'connected_at',
        'client_id',
        'clean_session',
        'play_state',
        'play_mode',
        'play_sound',
        'alias',
        'category_id',
        'file_cnt',
        'file_current',
        'play_timer_sum',
        'play_timer_cur',
        'memory_size',
        'trigger_modes',
        'battery_vol',
    ];
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

    public function getOneById(int $deviceId): array
    {
        return self::query()->find($deviceId)->toArray();
    }

    public function setTriggerModesAttribute($value)
    {
        $this->attributes['trigger_modes'] = json_encode($value);
    }

    public function getTriggerModesAttribute($value)
    {
        return json_decode($value);
    }

    public function category()
    {
        return $this->hasOne(CategoriesModel::class, 'id', 'category_id');
    }
}