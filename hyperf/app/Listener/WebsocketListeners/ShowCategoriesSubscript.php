<?php
/**
 * 获取分类
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Model\CategoriesModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Events\WebsocketEvents\ShowCategoriesEvent;
use Utils\WsMessage;

class ShowCategoriesSubscript implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ShowCategoriesEvent::NAME => 'handle'
        ];
    }

    public function handle(BaseEvent $event): void
    {
        $cList = (new CategoriesModel())->getAll();
        WsMessage::resSuccess($event, $cList);
    }
}