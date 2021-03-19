<?php
/**
 * Class UserFilesModel
 * @package App\Model
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Model;

class DeviceFilesModel extends BaseModel
{
    private $_fileName = 'device_files';

    /**
     * 添加设备文件
     * @param int $did
     * @param Int $file_id
     * @return int
     */
    public function createDeviceFile(int $did, Int $file_id): int
    {
        return (int) $this->insert($this->_fileName, ['device_id' => $did, 'file_id' => $file_id]);
    }
}