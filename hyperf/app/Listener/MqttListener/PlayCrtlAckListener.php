<?php

declare(strict_types=1);

namespace App\Listener\MqttListener;

use App\CacheModel\RedisCasheModel;
use App\Events\MqttEvents\PlayCrtlAckEvent;
use App\Events\WebsocketEvents\BaseEvent;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Swoole\Server;
use App\Model\{
    UsersModel,
    DevicesModel
};
use Utils\WsMessage;
use function PHPUnit\Framework\objectEquals;

/**
 * @Listener
 */
class PlayCrtlAckListener implements ListenerInterface
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
            PlayCrtlAckEvent::class
        ];
    }

    public function process(object $event)
    {
        $data = $event->data;
        $this->_replyUser($data);
    }

    /**
     *  回复用户
     */
    private function _replyUser(array $data)
    {
        $payload =  json_decode(substr($data['payload'], 8), true);
        $username = $data['from_username'];
        $user = UsersModel::where('username', $username)->first();
        $devcieId = $payload['deviceid'];
        $msgid = $payload['msgid'];
        $devcie = DevicesModel::query()->where('device_id', $devcieId)->first();
        $redis = ApplicationContext::getContainer()->get(RedisCasheModel::class);
        $fd = $redis->getFdByUid($user->id);
        $server = ApplicationContext::getContainer()->get(Server::class);
            $redisModel = ApplicationContext::getContainer()->get(RedisCasheModel::class);
            $msg = $redisModel->getControllerMessage($devcieId, (int) $msgid);
            $msg = json_decode(substr($msg, 8), true);
            $data = $msg['content'];
        // 更新设备参数
        $devices = DevicesModel::query()->where('device_id', $devcieId)->get();
        foreach ($devices as $device) {
            foreach ($data as $field => $v) {
                if (in_array($field, ['play_sound', 'play_state'])) {
                    $device->$field = $v;
                }
            }
            $device->save();
        }
        if ($server->isEstablished($fd)) {
            $url = sprintf('/me/devices/%d', $devcie['id']);
            $event = new BaseEvent($fd, 'put', $url);
            WsMessage::resSuccess($event, $data, $msgid);
        }
    }
}
