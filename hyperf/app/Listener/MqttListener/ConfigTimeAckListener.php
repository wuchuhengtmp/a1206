<?php

declare(strict_types=1);

namespace App\Listener\MqttListener;

use App\CacheModel\RedisCasheModel;
use App\Events\MqttEvents\ConfigTimeAckEvent;
use App\Events\WebsocketEvents\BaseEvent;
use App\Model\DevicesModel;
use App\Model\IntervalTimesModel;
use App\Model\UsersModel;
use App\Servics\WebsocketBroad2User;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Utils\Context;
use Utils\Helper;
use Utils\Message;
use Utils\WsMessage;

/**
 * @Listener
 */
class ConfigTimeAckListener implements ListenerInterface
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
            ConfigTimeAckEvent::class
        ];
    }

    public function process(object $event)
    {
        $data = $event->data;
        $message = Helper::decodeMsgByStr($data['payload']);
        $redisModel = ApplicationContext::getContainer()->get(RedisCasheModel::class);
        // 上次发送成功的定时消息报文
        $queueMsg = $redisModel->getControllerMessage($message['deviceid'], (int) $message['msgid']);
        $ctlMsg = Helper::decodeMsgByStr($queueMsg['message']);
        $ctlContent = $ctlMsg['content'];
        $device = DevicesModel::where('device_id', $ctlMsg['deviceid'])->first();
        $isHasConfigTime = IntervalTimesModel::where('device_id', $device->id)
            ->where('type_time', $ctlContent['type_time'])
            ->where('stime', $ctlContent['stime'])
            ->where('etime', $ctlContent['etime'])
            ->get()
            ->isNotEmpty();
        $e = new \App\Events\WebsocketEvents\BaseEvent(0, $queueMsg['method'], $queueMsg['url']);
        $user = UsersModel::query()->where('username', $data['from_username'])->first();
        if (!$isHasConfigTime) {
            $intervalTimes = new IntervalTimesModel();
            $intervalTimes->device_id = $device->id;
            $intervalTimes->type_time = $ctlContent['type_time'];
            $intervalTimes->stime = $ctlContent['stime'];
            $intervalTimes->etime = $ctlContent['etime'];
            $intervalTimes->ctime = $ctlContent['ctime'];
            $intervalTimes->save();
            ApplicationContext::getContainer()
                ->get(WebsocketBroad2User::class)
                ->sendSuccessMsg($e, [], $ctlMsg['msgid'], $user->id);
        } else {
            var_dump("error");
            ApplicationContext::getContainer()
                ->get(WebsocketBroad2User::class)
                ->sendErrorMsg($e, ['errorMsg' => '添加失败， 同样的定时已经存在'], $queueMsg['msgid'], $user->id);
        }
    }
}
