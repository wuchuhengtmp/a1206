<?php
/**
 * Class Http
 * @package App\Events
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events;

class Http
{

    public function onRequest(...$a)
    {
        var_dump($a);
    }
}