<?php
/**
 * 上报事件订阅
 * @package App\Listens\MqttListens
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\MqttListens;

use App\Dispatcher;
use App\Events\MqttEvents\ReportEvent;
use Simps\Server\Protocol\MQTT;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\Helper;
use Utils\Message;

class ReportSubscript implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ReportEvent::NAME => 'handle'
        ];
    }

    /**
     *  处理上报
     * @param ReportEvent $event
     */
    public function handle(ReportEvent $event): void
    {
        $msg = $event->currentMsg;
        $msg['content'] = Helper::fResContent($event, Helper::RES_SUCCESS,'report_data_ack');
        $content = Helper::parseContent($msg['content']);
        var_dump($content);
        $msg['cmd'] = MQTT::PUBCOMP;
        Message::getCurrentContent();
        $server = $event->getServer();
        $fd = $event->fd;
        $server->send($fd, MQTT::getAck($msg));
        // 获取设备参数
        $rMsg =  $msg;
        $rMsg['content'] = Helper::fResContent($event, null,'get_data_all');
//        $rMsg['cmd'] = MQTT::SUBACK;
//        $server->send($fd, MQTT::getAck($rMsg));
    }
}