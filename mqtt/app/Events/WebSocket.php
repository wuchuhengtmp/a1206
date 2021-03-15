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

        if (Helper::isJson($frame->data)) {
            $data = json_decode($frame->data, true);

        }
//        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
//        $server->push($frame->fd, 'this is server');
    }

    public static function onClose(Server $server, $fd)
    {
    }
}
