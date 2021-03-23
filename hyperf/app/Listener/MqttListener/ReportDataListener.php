<?php

declare(strict_types=1);

namespace App\Listener\MqttListener;

use App\Events\MqttEvents\AddDevicesEvent;
use App\Events\MqttEvents\BaseEvent;
use App\Events\MqttEvents\ReportDataEvent;
use App\Events\MqttEvents\UpdateDeviceEvent;
use App\Model\DevicesModel;
use App\Model\UsersModel;
use Hyperf\Event\Annotation\Listener;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
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
        // 保存设备
        $data = $event->data;
        $isUser = UsersModel::query()->where('username', $data['from_username'])->get()->isNotEmpty();
        if ($isUser) {
            $user = UsersModel::query()->where('username', $data['from_username'])->first();
            $payload = json_decode(substr($data['payload'], 8), true);
            $isDevices = DevicesModel::query()->where('user_id', $user->id)->where('device_id', $payload['deviceid'])
                ->get()
                ->isNotEmpty();
            $dispatcher = $this->container->get(EventDispatcherInterface::class);
            if (!$isDevices) {
                // 添加一台设备
                $dispatcher->dispatch(new AddDevicesEvent($event->data));
            } else {
                // 更新一台设备
                $dispatcher->dispatch(new UpdateDeviceEvent($event->data));
            }
        }
    }
}


