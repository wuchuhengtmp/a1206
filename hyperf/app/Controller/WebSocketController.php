<?php

declare(strict_types=1);
/**
 *
 * @link     https://wuchuheng.com
 * @author   wuchuheng <wuchuheng@163.com>
 * @license  MIT
 */
namespace App\Controller;

use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Swoole\Http\Request;
use Swoole\Websocket\Frame;
use Utils\WsRouteParser;

class WebSocketController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    public function onMessage($server, Frame $frame): void
    {
        $routes = config('websocketRoutes');
        var_dump($routes);
        WsRouteParser::run($frame->fd, $frame->data, $routes);
    }

    public function onClose($server, int $fd, int $reactorId): void
    {
        var_dump('closed');
    }

    public function onOpen($server, Request $request): void
    {
        $server->push($request->fd, 'Opened');
    }
}
