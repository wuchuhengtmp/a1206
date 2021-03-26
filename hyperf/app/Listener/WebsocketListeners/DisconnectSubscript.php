<?php
/**
 * Class DisconnectSubscript
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DisconnectSubscript implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [];
    }
}