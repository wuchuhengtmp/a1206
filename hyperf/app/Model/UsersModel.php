<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use PhpParser\Node\Expr\Cast\Object_;
use Utils\Encrypt;
use Utils\ReportFormat;

/**
 */
class UsersModel extends Model
{
    protected $hidden = [ 'password' ];

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
    protected $fillable = [
        'password',
        'username',
        'avatar',
        'nickname',
        'fat',
        'lng'
    ];
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
        $in = self::query();
        if (array_key_exists('password', $map)) {
            $map['password'] = Encrypt::hash($map['password']);
            $in->where('password', $map['password']);
        }
        $hasusers = $in->where('username', $map['username']) ->get();
        return (bool) $hasusers->count();
    }

    public function createUser(array $account): int
    {
        $account['password'] = Encrypt::hash($account['password']);
        $user =  new self();
        $user->password = $account['password'];
        $user->username = $account['username'];
        $user->save();
        $uid = $user->id;
        return $uid;
    }

    public function getUserById(int $uid): array
    {
        return self::query()->find($uid)->toArray();
    }

    public function getUserByDeviceId(string $deviceId)
    {
        $device = DevicesModel::where('device_id', $deviceId)->first();
        return self::where('id', $device->user_id)->first();
    }
}
