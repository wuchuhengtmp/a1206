<?php
/**
 * 向用户广播消息
 * @package App\Servics
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Servics;

use App\CacheModel\RedisCasheModel;
use App\Events\WebsocketEvents\BaseEvent;
use Hyperf\Utils\ApplicationContext;
use Swoole\Server;
use Utils\WsMessage;

class WebsocketBroad2User
{
    /**
     * 广播成功消息到当前用户的所有连接
     * @param BaseEvent $event
     * @param array $data
     * @param $msgid
     * @param int $uid
     */
    public function sendSuccessMsg(BaseEvent $event, array $data, $msgid, int $uid): void
    {
        $redis = ApplicationContext::getContainer()->get(RedisCasheModel::class);
        $fds = $redis->getFdByUid($uid);
        $server = ApplicationContext::getContainer()->get(Server::class);
        foreach ($fds as $fd) {
            if ($server->isEstablished($fd)) {
                $event = new BaseEvent($fd, $event->method, $event->url);
                WsMessage::resSuccess($event, $data, $msgid);
            }
        }

    }

    /**
     * 广播失败消息
     * @param BaseEvent $event
     * @param string $errMsg
     * @param $msgid
     * @param int $uid
     */
    public function sendErrorMsg(BaseEvent $event, array $errData, $msgid, int $uid): void
    {
        $redis = ApplicationContext::getContainer()->get(RedisCasheModel::class);
        $fds = $redis->getFdByUid($uid);
        $server = ApplicationContext::getContainer()->get(Server::class);
        foreach ($fds as $fd) {
            if ($server->isEstablished($fd)) {
                $event = new BaseEvent($fd, $event->method, $event->url);
                WsMessage::resError($event, $errData, $msgid);
            }
        }
    }
}
