<?php
/**
 * Class TcpServer
 * @package App\Events
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events;

class TcpServer
{
    public function handle($server, $fd)
    {
        echo "Client: Connect.\n";
    }
}