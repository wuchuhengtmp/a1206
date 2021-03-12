<?php

declare(strict_types=1);

/**
 * 订阅登录验证事件
 *
 * @package App\Listens\MqttListens
 * @author wuchuheng  <wuchuheng@163.com>
 */

namespace App\Listens\MqttListens;

use App\Events\MqttEvents\LoginEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LoginSubscript implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
             LoginEvent::NAME => 'handle'
        ];
    }

   public function handle()
   {
        echo " login event was trigged \n"; // 触发登录
   }
}