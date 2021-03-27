<?php

declare(strict_types=1);
/**
 *
 * @link     https://wuchuheng.com
 * @author   wuchuheng <wuchuheng@163.com>
 * @license  MIT
 */
namespace App\Controller;

use App\Dispatcher;
use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\DisconnectEvent;
use App\Exception\WsExceptions\BaseException;
use App\Exception\WsExceptions\ConnectBrokenException;
use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Swoole\Http\Request;
use Swoole\Websocket\Frame;
use Utils\Context;
use Utils\WsMessage;
use Utils\WsRouteParser;

class WebSocketController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    public function onMessage($server, Frame $frame): void
    {
        Context::set($frame->fd, 'server', $server);
        Context::set($frame->fd, 'frame', $frame);
        $routes = config('websocketRoutes');
        try {
            WsRouteParser::run($frame->fd, $frame->data, $routes);
        } catch (\Throwable $e) {
            if ($e instanceof ConnectBrokenException) {
                echo "连接中断:";
                var_dump($e->getFile());
                var_dump($e->getLine());
                // 连接断开异常
                // todo: ...
            } else if ($e instanceof BaseException) {
                $event = new BaseEvent($frame->fd, $e->method, $e->url);
                WsMessage::resError($event, ['errorCode' => $e->errorCode, 'errorMsg' => $e->errorMsg]);
            } else {
                throw  $e;
            }
        }
    }

    public function onClose($server, int $fd, int $reactorId): void
    {
        Dispatcher::getInstance()->dispatch(new DisconnectEvent($fd, '', ''));
    }

    public function onOpen($server, Request $request): void
    {
        $server->push($request->fd, 'Opened');
    }
}
