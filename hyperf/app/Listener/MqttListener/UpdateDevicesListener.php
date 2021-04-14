<?php

declare(strict_types=1);

namespace App\Listener\MqttListener;

use App\CacheModel\RedisCasheModel;
use App\Events\MqttEvents\AddDevicesEvent;
use App\Events\MqttEvents\UpdateDeviceEvent;
use App\Model\DevicesModel;
use App\Model\UsersModel;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;

/**
 * @Listener
 */
class UpdateDevicesListener implements ListenerInterface
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
        return [ UpdateDeviceEvent::class ];
    }

    public function process(object $event)
    {
        $data = $event->data;
        $user = UsersModel::query()->where('username', $data['from_username'])->first();
        $payload = json_decode(substr($data['payload'], 8), true);
        $device = DevicesModel::query()->where('user_id', $user->id)->where('device_id', $payload['deviceid'])
            ->first();
        $redis = ApplicationContext::getContainer()->get(RedisCasheModel::class);
        $registerInfo = $redis->getRegisterInfoByClientId($event->data['from_client_id']);
        $connectInfo = $redis->getConnectInfoByClientId($event->data['from_client_id']);
        $device->ip_address = $connectInfo['ipaddress'];
        $device->keepalive = $connectInfo['keepalive'];
        $device->protocol = "mqtt_" . $connectInfo['proto_ver'];
        $device->status = 'online';
        $device->version = $registerInfo['content']['version'];
        $device->last_ack_at = date("Y-m-d H:i:s", time());
//        $device->connected_at = date("Y-m-d H:i:s", time());
        $device->client_id = $connectInfo['client_id'];
        $device->clean_session = 0;
        $device->play_state = $payload['content']['play_state'];
        $device->vender = $registerInfo['content']['vender'];
        $device->play_mode = $payload['content']['play_mode'];
        $device->play_sound = $payload['content']['play_sound'];
        $device->alias = $payload['deviceid'];
        $device->category_id = 1;
        $device->file_cnt = $payload['content']['file_cnt'];
        $device->file_current = $payload['content']['file_current'];
        $device->play_timer_sum = $payload['content']['play_timer_sum'];
        $device->play_timer_cur = $payload['content']['play_timer_cur'];
        $device->memory_size = $payload['content']['memory_size'];
        $device->trigger_modes = $payload['content']['trigger_modes'];
        $device->battery_vol = $payload['content']['battery_vol'];
        $device->save();
    }
}
