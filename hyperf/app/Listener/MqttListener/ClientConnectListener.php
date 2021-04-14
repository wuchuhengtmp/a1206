<?php

declare(strict_types=1);

namespace App\Listener\MqttListener;

use App\CacheModel\RedisCasheModel;
use App\Events\MqttEvents\ClientConnectedEvent;
use App\Model\DevicesModel;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;

/**
 * @Listener
 */
class ClientConnectListener implements ListenerInterface
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
            ClientConnectedEvent::class
        ];
    }

    public function process(object $event)
    {
        $container = ApplicationContext::getContainer();
        $redisModel = $container->get(RedisCasheModel::class);
        $redisModel->setConnectInfo($event->data['client_id'], $event->data);
        // 更新连接时间
        $device = DevicesModel::where('device_id', $event->data['client_id'])->first();
        if($device) {
            $device->connected_at = date('Y-m-d H:i:s', time());
            $device->save();
        }
    }
}
