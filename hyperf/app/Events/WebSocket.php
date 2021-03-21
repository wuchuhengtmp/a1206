<?php

declare(strict_types=1);
/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */
namespace App\Events;

use App\Events\WebsocketEvents\BaseEvent;
use App\Exception\WsExceptions\BaseException;
use App\Exception\WsExceptions\ConnectBrokenException;
use Swoole\WebSocket\Server;
use Utils\Context;
use Utils\Helper;
use Utils\JWT;
use Utils\WsMessage;
use Utils\WsRouteParser;

class WebSocket
{
    public static function onOpen(Server $server, $request)
    {
        echo "server: handshake success with fd{$request->fd}\n";
    }

    public static function onMessage(Server $server, $frame)
    {
        Context::set($frame->fd, 'server', $server);
        Context::set($frame->fd, 'frame', $frame);
        $routes = config('websocketRoutes');
        try {
            WsRouteParser::run($frame->fd, $frame->data, $routes);
        } catch (BaseException $e) {
            if ($e instanceof ConnectBrokenException) {
                echo "连接中断:";
                var_dump($e->getFile());
                var_dump($e->getLine());
                // 连接断开异常
                // todo: ...
            } else {
                $event = new BaseEvent($frame->fd, $e->method, $e->url);
                WsMessage::resError($event, ['errorCode' => $e->errorCode, 'errorMsg' => $e->errorMsg]);
            }
        }
    }

    public static function onClose(Server $server, $fd)
    {

    }
}
