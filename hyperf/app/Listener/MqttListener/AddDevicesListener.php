<?php

declare(strict_types=1);

namespace App\Listener\MqttListener;

use App\CacheModel\RedisCasheModel;
use App\Events\MqttEvents\AddDevicesEvent;
use App\Model\DevicesModel;
use App\Model\UsersModel;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;

/**
 * @Listener
 */
class AddDevicesListener implements ListenerInterface
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
            AddDevicesEvent::class
        ];
    }

    public function process(object $event)
    {
        $data = $event->data;
        $username = $data['from_username'];
        $user = UsersModel::where('username', $username)->first();
        $payload = json_decode(substr($data['payload'], 8), true);
        $device = new DevicesModel();
        $redis = ApplicationContext::getContainer()->get(RedisCasheModel::class);
        $connectInfo = $redis->getConnectInfoByClientId($event->data['from_client_id']);
        $registerInfo = $redis->getRegisterInfoByClientId($event->data['from_client_id']);
        $device->device_id = $payload['deviceid'];
        $device->user_id = $user->id;
        $device->ip_address = $connectInfo['ipaddress'];
        $device->keepalive = $connectInfo['keepalive'];
        $device->protocol = "mqtt_" . $connectInfo['proto_ver'];
        $device->status = 'online';
        $device->version = $registerInfo['content']['version'];
        $device->last_ack_at = date("Y-m-d H:i:s", time());
        $device->connected_at = date("Y-m-d H:i:s", time());
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
        $res = $device->save();
    }
}
