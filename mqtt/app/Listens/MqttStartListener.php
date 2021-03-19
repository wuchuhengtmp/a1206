<?php
/**
 * Class MqttStartListener
 * @package App\Listens
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens;

use Simps\DB\BaseModel;
use Simps\Singleton;
use Swoole\Coroutine\MySQL;

class MqttStartListener
{
    use Singleton;

    public function handle($server): void
    {
        if ($server->setting['open_mqtt_protocol']) {
            go(function () {
//                $this->delete('subscriptions');
//                $res = $swoole_mysql->query('DELETE FROM ');
            });
        }
    }
}