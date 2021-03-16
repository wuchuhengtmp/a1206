<?php
/**
 * Class Tcp
 * @package App\Events
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events;

class Tcp
{

    public function onReceive(...$server)
    {
        var_dump($server);
    }
}