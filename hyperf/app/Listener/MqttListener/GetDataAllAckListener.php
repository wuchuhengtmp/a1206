<?php

declare(strict_types=1);

namespace App\Listener\MqttListener;

use App\Events\MqttEvents\GetDataAllAckEvent;
use Hyperf\Event\Annotation\Listener;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;

/**
 * @Listener
 */
class GetDataAllAckListener implements ListenerInterface
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
            GetDataAllAckEvent::class
        ];
    }

    public function process(object $event)
    {
        var_dump("hello\n");
    }
}
