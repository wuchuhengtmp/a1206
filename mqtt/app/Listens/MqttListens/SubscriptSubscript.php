<?php
/**
 * 订阅事件
 * @package App\Listens\MqttListens
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\MqttListens;

use App\Events\MqttEvents\SubscriptEvent;
use App\Model\SubscriptionsModel;
use Simps\DB\BaseModel;
use Simps\Server\Protocol\MQTT;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\Context;
use Utils\Message;

class SubscriptSubscript extends BaseModel implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
           SubscriptEvent::NAME => 'handle'
        ];
    }

    public function handle(SubscriptEvent $event): void
    {
        $subModel = new SubscriptionsModel($event->fd);
        $subMsg = Message::getSubscriptMsg($event->fd)->res;
        $map = ['fd' => $event->fd];
        $payload = [];
        // 遍历订阅的主题 没有添加有则更新
        foreach ($subMsg['topics'] as $topic => $qos) {
            $payload[] = $qos;
            $map['topic'] = $topic;
            $hasSub = $subModel->getFirstByMap($map);
            $valums = [
                'topic' => $topic,
                'fd' => $event->fd,
                'qos' => $qos
            ];
            if ($hasSub->isError) {
                $subModel->createSub($valums);
            } else {
                $subModel->updateSub($hasSub->res['id'], $valums);
            }
        }
        $resData = [
            'cmd' => MQTT::SUBACK,
            'message_id' => $subMsg['message_id'],
            'payload' => $payload
        ];
        Context::getServer($event->fd)->res->send($event->fd, MQTT::getAck($resData));
    }
}