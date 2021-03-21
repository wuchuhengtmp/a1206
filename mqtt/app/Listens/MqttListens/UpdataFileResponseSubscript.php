<?php
/**
 * Class UpdataFileResponseSubscript
 * @package App\Listens\MqttListens
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\MqttListens;

use App\Events\MqttEvents\BaseEvent;
use App\Events\MqttEvents\UpdataFileResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UpdataFileResponseSubscript implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            UpdataFileResponseEvent::NAME => 'handle'
        ];
    }

    /**
     * @param BaseEvent $event
     */
    public function handle(BaseEvent  $event): void
    {
        $msg = $event->currentMsg;
    }
}