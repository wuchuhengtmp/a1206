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
use Utils\Message;

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
       $hasConnectMsg = Message::getConnectMsg($event->fd);
       if ($hasConnectMsg->isError) {
           $this->_rejectConnect($event->fd);
           return;
       }
       $data = $hasConnectMsg->res;
       if ($this->has('users', [
           'username'=> $data['username'],
           'password' => Encrypt::hash($data['password'])
       ])) {
           // 发布已登录事件
           Dispatcher::getInstance()->dispatch(new LoggedEvent($event->fd), LoggedEvent::NAME);
           $this->_acceptConnect($event->fd);
       } else {
           $this->_rejectConnect($event->fd);
       }
   }

    /**
     *  接受连接
     */
   private function _acceptConnect(int $fd)
   {
       $hasServer = $server = Context::getServer($fd);
       $server = $hasServer->res;
       $server->send($fd, MQTT::getAck([
           'cmd' => 2,
           'code' => 0
       ]));
   }

   /**
    *  拒绝连接
    */
   private function _rejectConnect(int $fd)
   {
       $hasServer = Context::getServer($fd);
       $server = $hasServer->res;
       $server->send($fd, MQTT::getAck([
           'cmd' => 2,
           'code' => 5
       ]));
       $server->close($fd);
   }

}