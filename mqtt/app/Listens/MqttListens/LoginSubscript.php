<?php

declare(strict_types=1);

/**
 * 订阅登录验证事件
 * @package App\Listens\MqttListens
 * @author wuchuheng  <wuchuheng@163.com>
 */

namespace App\Listens\MqttListens;

use App\Dispatcher;
use App\Events\MqttEvents\LoginEvent;
use App\Events\MqttEvents\LoggedEvent;
use Simps\DB\BaseModel;
use Simps\Server\Protocol\MQTT;
use Swoole\Coroutine;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\Context;
use Utils\Encrypt;

class LoginSubscript extends BaseModel implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            LoginEvent::NAME => 'handleLogin',
        ];
    }

    /**
     *  登录
     * @param LoginEvent $event
     */
   public function handleLogin(LoginEvent $event): void
   {
       $data = Context::get('data');
       if ($this->has('users', [
           'username'=> $data['username'],
           'password' => Encrypt::hash($data['password'])
       ])) {
           // 发布已登录事件
           Dispatcher::getInstance()->dispatch(new LoggedEvent(), LoggedEvent::NAME);
           $this->_acceptConnect();
       } else {
           $this->_rejectConnect();
       }
   }

    /**
     *  接受连接
     */
   private function _acceptConnect()
   {

       $server = Context::getServer();
       $fd = Context::getFd();
       $data = Context::getData();
       $fromId = Context::getFromId();

       $server->send($fd, MQTT::getAck([
           'cmd' => 2,
           'code' => 0
       ]));
   }

   /**
    *  拒绝连接
    */
   private function _rejectConnect()
   {
       $server = Context::getServer();
       $fd = Context::getFd();
       $server->send($fd, MQTT::getAck([
           'cmd' => 2,
           'code' => 5
       ]));
       $server->close($fd);
   }

}