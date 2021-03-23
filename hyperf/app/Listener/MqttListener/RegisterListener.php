<?php

declare(strict_types=1);

namespace App\Listener\MqttListener;

use App\CacheModel\RedisCasheModel;
use App\Events\MqttEvents\RegisterEvent;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;

/**
 * @Listener
 */
class RegisterListener implements ListenerInterface
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
            RegisterEvent::class
        ];
    }

    public function process(object $event)
    {
        $data = $event->data;
        $payload = \json_decode(substr($data['payload'],8), true);
        $cashe = ApplicationContext::getContainer()->get(RedisCasheModel::class);
        $cashe->setRegisterInfo($event->data['from_client_id'], $payload);
    }
}
