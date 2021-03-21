<?php
/**
 * Class UpdataFileSubscript
 * @package App\Listens\MqttListens
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\MqttListens;

use App\Events\MqttEvents\BaseEvent;
use App\Events\MqttEvents\UpdataFileEvent;
use App\Model\SubscriptionsModel;
use Simps\Server\Protocol\MQTT;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\Helper;

class UpdataFileSubscript implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            UpdataFileEvent::NAME => 'handle'
        ];
    }

    /**
     * @param BaseEvent $event
     */
    public function handle(BaseEvent  $event):void
    {
        $server = $event->getServer();
        $msg = $event->currentMsg;
        $topic = $msg['topic'];
        $msg['content'] = Helper::fResContent($event, Helper::RES_SUCCESS,'report_data_ack');
        $msg['cmd'] = MQTT::PUBCOMP;
        $msg['message_id'] = time();
        $fd = $event->fd;
        $server->send($fd, MQTT::getAck($msg));
        $subjects = (new SubscriptionsModel($event->fd))->getDataByTopic($topic);
        $msg['cmd'] = MQTT::PUBLISH;
        foreach ($subjects as $subject) {
            $sfd = (int)$subject['fd'];
            if ($server->exist($sfd)) {
                var_dump($sfd);
                $server->send($sfd, MQTT::getAck($msg));
            }
        }
    }
}