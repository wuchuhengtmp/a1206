<?php

declare(strict_types=1);

namespace App\Listener\MqttListener;

use App\CacheModel\RedisCasheModel;
use App\Events\MqttEvents\GetDataAllAckEvent;
use App\Events\WebsocketEvents\BaseEvent;
use App\Model\DevicesModel;
use App\Model\FilesModel;
use App\Model\UsersModel;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Swoole\Server;
use Utils\Context;
use Utils\Helper;
use Utils\Message;
use Utils\WsMessage;

/**
 * @Listener
 */
class GetDataAllAckListener implements ListenerInterface
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
            GetDataAllAckEvent::class
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
        $server = ApplicationContext::getContainer()->get(Server::class);
        $redisModel = ApplicationContext::getContainer()->get(RedisCasheModel::class);
        $data = $redisModel->getControllerMessage($devcieId, (int) $msgid);
        $msg = $data['message'];
        $msg = json_decode(substr($msg, 8), true);
        $fds = $redis->getFdByUid($user->id);
        $fileCurlName = $payload["content"]['file_cur_name'];
        $payload["content"]['file_cur_name'] = (new FilesModel())->getUrlByName($fileCurlName);
        $url = $data['url'];
        foreach ($fds as $fd) {
            if ($server->isEstablished($fd)) {
                $event = new BaseEvent($fd, 'put', $url);
                WsMessage::resSuccess($event, $payload['content'], $msg['msgid']);
            }
        }
    }
}
