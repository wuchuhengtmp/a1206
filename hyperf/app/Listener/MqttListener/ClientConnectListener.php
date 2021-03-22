<?php

declare(strict_types=1);

namespace App\Listener\MqttListener;

use App\Events\MqttEvents\ClientConnectedEvent;
use Hyperf\Event\Annotation\Listener;
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
        var_dump("连接事件");
    }
}
