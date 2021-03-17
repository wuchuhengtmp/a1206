<?php
/**
 * Class UsersModel
 * @package App\BaseModel
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Console\Helper\HelperSet;
use Utils\Encrypt;
use Utils\Message;
use Utils\ReportFormat;

class UsersModel extends BaseModel
{
    private $tableName = 'users';

    /**
     *  mqtt连接 获取用户
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

    /**
     *  获取一个用户
     * @param array $map
     * @return ReportFormat
     */
    public function getUserByAccount(array $map): ReportFormat
    {
        $map['password'] = Encrypt::hash($map['password']);
        $res = new ReportFormat();
        if ($this->has($this->tableName, $map)) {
            $res->res = $this->get($this->tableName, '*', $map);
            $res->isError = false;
            return $res;
        } else {
            return $res;
        }
    }

    /**
     *  添加个用户
     * @param array $account
     */
    public function createUser(array $account): int
    {
        $account['password'] = Encrypt::hash($account['password']);
        $userId = $this->insert($this->tableName, $account);
        return (int) $userId;
    }

    /**
     *  是否有这个用户
     * @param array $map
     * @return bool
     */
    public function hasUser(array $map): bool
    {
        if (array_key_exists('password', $map)) {
            $map['password'] = Encrypt::hash($map['password']);
        }
       return $this->has($this->tableName, $map);
    }

    /**
     * @param int $uid
     * @return false|mixed
     */
    public function getUserById(int $uid)
    {
        return $this->get($this->tableName, '*', ['id' => $uid]);
    }

}