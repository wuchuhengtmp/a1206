<?php
/**
 * 订阅断开事件
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\CacheModel\RedisCasheModel;
use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\DisconnectEvent;
use App\Model\UsersModel;
use Hyperf\Utils\ApplicationContext;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DisconnectSubscript implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            DisconnectEvent::class => 'handle'
        ];
    }

    public function handle(BaseEvent  $event)
    {
        $redisModel = ApplicationContext::getContainer()->get(RedisCasheModel::class);
        $uid = $redisModel->getUidByFd($event->fd);
        if ($uid !== 0) {
            $redisModel->uidUnbindFd($uid, $event->fd);
        }
    }
}