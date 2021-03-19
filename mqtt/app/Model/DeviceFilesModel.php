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
    private $_tableName = 'device_files';

    /**
     * 添加设备文件
     * @param int $did
     * @param Int $file_id
     * @return int
     */
    public function createDeviceFile(int $did, Int $file_id): int
    {
        return (int) $this->insert($this->_tableName, ['device_id' => $did, 'file_id' => $file_id]);
    }

    /**
     * 有没有这个设备文件
     * @param int $deviceId
     * @param int $fileId
     * @return bool
     */
    public function hasDeviceFile(int $deviceId, int $fileId): bool
    {
        return $this->has($this->_tableName, ['device_id' => $deviceId, 'file_id' => $fileId]);
    }


    /**
     * 删除设备文件
     * @param $deviceId
     * @param $fileId
     * @return bool
     */
    public function destroy($deviceId, $fileId): bool
    {
        // todo 这里删除文件要 判断文件是不是系统默认的， 如果不是要删除，且硬盘也要删除
        $res = $this->delete($this->_tableName, ['file_id' => $fileId, 'device_id' => $deviceId]);
        return (bool) $res->rowCount();
    }

    /**
     * @param int $uid
     * @param int $deviceId
     * @return array
     */
    public function getDataById(int $deviceId): array
    {
        return $this->query(" SELECT * FROM device_files WHERE device_id = $deviceId ")->fetchAll();
    }

    /**
     * @param array $map
     * @return array
     */
    public function hasOne(array $map): bool
    {
        return $this->has($this->_tableName, $map);
    }
}