<?php

declare(strict_types=1);

namespace App\Listener\MqttListener;

use App\Events\MqttEvents\BaseEvent;
use App\Events\MqttEvents\ReportDataEvent;
use Hyperf\Event\Annotation\Listener;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Utils\Helper;
use Utils\MqttClient;

/**
 * @Listener
 */
class ReportDataListener implements ListenerInterface
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
            ReportDataEvent::class
        ];
    }

    public function process($event)
    {
        $payload = $event->data['payload'];
        $content = $payload;
        $content = json_decode(substr($content, 8), true);
        $content['command'] = 'report_data_ack';
        $content['content'] = MqttClient::SUCCESS_CONTENT;
        $msg = Helper::fMqttMsg($content);
        $mqttClient = new MqttClient();
        $topic = sprintf('%s_%s', $content['type'], $content['deviceid']);
        go(function () use ($msg, $topic, $mqttClient) {
            $res = $mqttClient->getClient()->publish($topic, $msg, 1);
        });
    }
}


