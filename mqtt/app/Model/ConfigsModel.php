<?php
/**
 * Class ConfigsModel
 * @package App\Model
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Model;

class ConfigsModel extends BaseModel
{
    private $_tableName = 'configs';

    /**
     *  获取默认音频配置
     * @return array
     */
    public function getDefaulitAudio(): array
    {
        return $this->query("SELECT * FROM {$this->_tableName} WHERE `name` LIKE 'DEFAULT_AUDIO_%'")->fetchAll();
    }
}