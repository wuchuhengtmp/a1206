<?php
/**
 * Class HttpServer
 * @package App\Events
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events;

class HttpServer
{
    public function onRequest($request, $response)
    {
        $response->end('<h1>Hello Swoole. #' . rand(1000, 9999) . '</h1>');
    }
}