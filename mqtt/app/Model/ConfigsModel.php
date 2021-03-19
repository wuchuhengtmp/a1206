<?php
/**
 * Class ConfigsModel
 * @package App\Model
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Model;

use App\Storages\Storage;

class ConfigsModel extends BaseModel
{
    private $_tableName = 'configs';

    /**
     *  获取默认音频配置
     * @return array
     */
    public function getDefaultAudio(): array
    {
        $data = $this->query("
            SELECT f.path, disk, c.id, f.id as file_id FROM configs as c
            INNER JOIN files as f ON f.id = c.`value`
            WHERE `name` like 'DEFAULT_AUDIO_%'
        ")->fetchAll();
        foreach ($data as &$e) {
            $e['url'] = (new Storage())->disk($e['disk'])->url($e['path']);
            unset($e['path'], $e['disk']);
            $urlInfo =  pathinfo($e['url']);
            preg_match('/^([^\.]+)/', $urlInfo['basename'], $mRes);
            $e['name'] = $mRes[1];
        }
        return $data;
    }
}