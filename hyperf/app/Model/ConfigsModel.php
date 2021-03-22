<?php

declare (strict_types=1);
namespace App\Model;

use App\Storages\Storage;
use Hyperf\DbConnection\Db;
use Hyperf\DbConnection\Model\Model;
/**
 */
class ConfigsModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'configs';
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

    public function getDefaultAudio(): array
    {
        $data = Db::select(" SELECT f.path, disk, c.id, f.id as file_id FROM configs as c
            INNER JOIN files as f ON f.id = c.`value`
            WHERE `name` like 'DEFAULT_AUDIO_%'");
        foreach ($data as &$e) {
            $e = (array) $e;
            $disk = $e['disk'];
            $url = make(\Hyperf\Filesystem\FilesystemFactory::class)->get($disk)->getAdapter();
            $e['url'] = $url->getUrl($e['path']);
            unset($e['path'], $e['disk']);
            $urlInfo =  pathinfo($e['url']);
            preg_match('/^([^\.]+)/', $urlInfo['basename'], $mRes);
            $e['name'] = $mRes[1];
        }
        return $data;
    }
}