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
        Message::send($event, Helper::RES_SUCCESS,'report_data_ack');
    }

}