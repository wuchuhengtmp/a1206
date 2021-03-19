<?php
/**
 * Class DevicesModel
 * @package App\BaseModel
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Model;

use App\Storages\Storage;
use Utils\Context;
use Utils\Message;
use Utils\ReportFormat;

class DevicesModel extends BaseModel
{
    public $tableName = 'devices';

    /**
     *  添加一台设备或者更新一台设备
     */
    public function addDeviceOrUpdate(): ReportFormat
    {
        $res = new ReportFormat();
        $cuser = (new UsersModel($this->fd))->getCurrentUser()->res;
        $content = Message::getRegisterContent($this->fd)->res;
        $server = Context::getServer($this->fd)->res;
        $cInfo = $server->getClientInfo($this->fd);
        $connectMsg = Message::getConnectmsg($this->fd)->res;
        $rMsg = Message::getRegisterContent($this->fd)->res;
        $device = [
            'device_id' =>  $content['deviceid'],
            'user_id' => $cuser['id'],
            'ip_address' => $cInfo['remote_ip'],
            'status' => 'online',
            'keepalive' => $connectMsg['keepalive'],
            'protocol' => $connectMsg['protocol_name'],
            'client_id' => $connectMsg['client_id'],
            'clean_session' => $connectMsg['clean_session'],
            'version' => $rMsg['content']['version'],
            'vender' => $rMsg['content']['vender'],
        ];
        if ($this->isExists()) {
            // 更新一台设备
            $this->update($this->tableName, $device, [
                'device_id' =>  $content['deviceid'],
                'user_id' => $cuser['id'],
            ]);
        } else {
            // 添加一台设备
            $device['alias'] = $device['device_id'];
            $this->insert($this->tableName, $device);
        }
        $res->isError = false;
        return $res;
    }

    /**
     * 当前连接的设备是否已经在当前用户的名下
     *
     */
    public function isExists(): bool
    {
        $cuser = (new UsersModel($this->fd))->getCurrentUser()->res;
        $content = Message::getRegisterContent($this->fd)->res;
        $map = [
            'device_id' => $content['deviceid'],
            'user_id' => $cuser['id']
        ];
        $res = $this->has($this->tableName, $map);
        return $res;
    }


    /**
     * @param int $uid
     * @return array
     */
    public function getDevicesByUid(int $uid): array
    {
        return $this->select($this->tableName, '*', ['user_id' => $uid]);
    }

    public function uploadDevice($deviceid, array $columns): void
    {
        $this->update($this->tableName, $columns, ['device_id' => $deviceid]);
    }


    /**
     * @param int $deviceId
     * @return array
     */
    public function getFilesByDeviceId(int $deviceId): array
    {
        $files = $this->query(sprintf("
            SELECT
                f.*,
                df.id as deviceId
            FROM
                device_files as df
                INNER JOIN files as f ON f.id = df.file_id
            WHERE
                df.device_id = %d 
        ", $deviceId))->fetchAll();
        $fileIds = array_column($files, 'id');
        $defaultFiles = (new ConfigsModel($this->fd))->getDefaultAudio();
        foreach ($defaultFiles as &$dFile) {
            $dFile['isSelect'] = in_array($dFile['file_id'], $fileIds);
            $dFile['id'] = $dFile['file_id'];
            unset($dFile['file_id']);
        }
        return $defaultFiles;
    }

    /**
     * @param int $deviceId
     * @return array
     */
    public function getOneById(int $deviceId): array
    {
        return $this->get($this->tableName, '*', ['id' => $deviceId]);
    }
}