<?php
use Swoole\Coroutine;
use Swoole\Coroutine\Http\Client;
use function Swoole\Coroutine\run;

run(function () {
    $client = new Client('183.4.135.6', 9602);
    $ret = $client->upgrade('/');
    if ($ret) {
        while(true) {
            var_dump("hello\n");
            $client->push('hello');
            var_dump($client->recv());
            Coroutine::sleep(0.1);
        }
    }
});

