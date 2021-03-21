<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use Utils\Encrypt;
use Utils\ReportFormat;

/**
 */
class UsersModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
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

    /**
     *  获取一个用户
     * @param array $map
     * @return ReportFormat
     */
    public function getUserByAccount(array $map): ReportFormat
    {
        $map['password'] = Encrypt::hash($map['password']);
        $res = new ReportFormat();
        $user = self::query()
            ->where('password', $map['password'])
            ->where('username', $map['username'])
            ->get();
        if ($user->isNotEmpty()) {
            $res->res = $user->first()->toArray();
            $res->isError = false;
            return $res;
        } else {
            return $res;
        }
    }

    public function hasUser(array $map): bool
    {
        $map['password'] = Encrypt::hash($map['password']);
        $hasusers = self::query()
            ->where('username', $map['username'])
            ->where('password', $map['password'])
            ->get();
        return (bool) $hasusers->count();
    }

}