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
use App\Model\DevicesModel;
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
        // 更新设备数据
        $content = Helper::parseContent($msg['content'])->res;
        $deviceId = $content['deviceid'];
        $colums = [
            'play_state' => $content['content']['play_state'],
            'play_mode' => $content['content']['play_mode'],
            'play_sound' => $content['content']['play_sound'],
            'file_cnt' => $content['content']['file_cnt'],
            'file_current' => $content['content']['file_current'],
            'play_timer_sum' => $content['content']['play_timer_sum'],
            'play_timer_cur' => $content['content']['play_timer_cur'],
            'battery_vol' => $content['content']['battery_vol'],
            'status' => 'online',
            'memory_size' => $content['content']['memory_size'],
            'trigger_modes' => json_encode( $content['content']['trigger_modes'])
        ];
        (new DevicesModel($event->fd))->uploadDevice($deviceId, $colums);
        $msg['content'] = Helper::fResContent($event, Helper::RES_SUCCESS,'report_data_ack');
        $msg['cmd'] = MQTT::PUBCOMP;
        $server = $event->getServer();
        $fd = $event->fd;
        $server->send($fd, MQTT::getAck($msg));
        // 获取设备参数
//        $rMsg =  $msg;
//        $rMsg['content'] = Helper::fResContent($event, null,'get_data_all');
//        $rMsg['cmd'] = MQTT::SUBACK;
//        $server->send($fd, MQTT::getAck($rMsg));
    }
}