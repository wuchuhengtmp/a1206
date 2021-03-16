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

use Swoole\WebSocket\Server;
use Utils\Context;
use Utils\Helper;
use Utils\JWT;
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
        $hasMatchRoute = WsRouteParser::run($frame->fd, $frame->data, $routes);
        if ($hasMatchRoute->isError) {
            echo "the route was not matched \n";
            // :todo 路由没有匹配到
        }
    }

    public static function onClose(Server $server, $fd)
    {

    }
}
