<?php
/**
 * Class UsersModel
 * @package App\BaseModel
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Model;

use Utils\Encrypt;
use Utils\Message;
use Utils\ReportFormat;

class UsersModel extends BaseModel
{
    private $tableName = 'users';

    /**
     *  获取用户
     */
    public function getCurrentUser(): ReportFormat
    {
        $fd = $this->fd;
        $res = new ReportFormat();
        $connectMsg = Message::getConnectMsg($fd)->res;
        $map = [
            'password' => Encrypt::hash($connectMsg['password']),
            'username' => $connectMsg['username']
        ];
        $user = $this->get($this->tableName, '*', $map);
        $isUser = $this->has($this->tableName, $map);
        if ($isUser) {
            $res->isError = false;
            $res->res = $user;
        }
        return $res;
    }
}