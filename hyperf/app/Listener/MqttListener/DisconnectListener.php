<?php

declare(strict_types=1);

namespace App\Listener\MqttListener;

use App\CacheModel\RedisCasheModel;
use App\Events\MqttEvents\DisconnectEvent;
use App\Model\UsersModel;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Utils\WsMessage;

/**
 * @Listener
 */
class DisconnectListener implements ListenerInterface
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
            DisconnectEvent::class
        ];
    }

    public function process(object $event)
    {
        $data = $event->data;
        $redis = ApplicationContext::getContainer()->get(RedisCasheModel::class);
        $hasUser = UsersModel::query()->where('username', $data['username'])->first();
        var_dump($hasUser, $data);
    }
}
