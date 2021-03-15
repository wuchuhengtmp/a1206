<?php
/**
 * Class DevicesModel
 * @package App\BaseModel
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Model;

use Utils\Context;
use Utils\Message;
use Utils\ReportFormat;

class DevicesModel extends BaseModel
{
    private $tableName = 'devices';

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
            'vender' => $rMsg['content']['vender']
        ];
        if ($this->isExists()) {
            // 更新一台设备
            $this->update($this->tableName, $device, [
                'device_id' =>  $content['deviceid'],
                'user_id' => $cuser['id'],
            ]);
        } else {
            // 添加一台设备
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
}