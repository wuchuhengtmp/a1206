<?php

declare(strict_types=1);

namespace App\Listener\MqttListener;

use App\CacheModel\RedisCasheModel;
use App\Events\MqttEvents\DisconnectEvent;
use App\Events\WebsocketEvents\BaseEvent;
use App\Model\DevicesModel;
use App\Model\UsersModel;
use Cassandra\DefaultSession;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Swoole\Server;
use Utils\Helper;
use Utils\WsMessage;

/**
 * @Listener
 */
class DisconnectListener implements ListenerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function listen(): array
    {
        return [
            DisconnectEvent::class
        ];
    }

    public function process(object $event)
    {
        $data = $event->data;
        $deviceId = $data['client_id'];
        $redis = ApplicationContext::getContainer()->get(RedisCasheModel::class);
        $hasUser = ApplicationContext::getContainer()->get(UsersModel::class)->getUserByDeviceId($deviceId);
        $isDevice = DevicesModel::query()->where('device_id', $deviceId)->get()->isNotEmpty();
        if ($hasUser && $isDevice) {
            // 更新设备状态
            $device = DevicesModel::query()->where('device_id', $deviceId)->first();
            $device->status = 'offline';
            $device->save();
            $user = $hasUser;
            // 设备列表推送给在线的用户
            $devicves = DevicesModel::query()->where('user_id', $user->id)->get();
            $pushData = [];
            foreach ($devicves as $devicve) {
                $tmp = [];
                $tmp['id'] = $devicve->id;
                $tmp['category_id'] = $devicve->category_id;
                $tmp['status'] = $devicve->status;
                $tmp['alias'] = $devicve->alias;
                $tmp['last_ack_at'] = $devicve->last_ack_at;
                $tmp['category_name'] = $devicve->category->name;
                $pushData[] = $tmp;
            }
            $fds = $redis->getFdByUid($user->id);
            $server = ApplicationContext::getContainer()->get(Server::class);
            foreach ($fds as $fd) {
                $event = new BaseEvent($fd, 'get', '/me/devcies');
                if ($server->isEstablished($fd)) {
                    WsMessage::resSuccess($event, $pushData, '00000000000');
                }
            }
        }
    }
}
