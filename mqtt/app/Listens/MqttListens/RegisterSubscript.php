<?php
/**
 * 订阅设备注册事件
 *
 * @package App\Listens\MqttListens
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\MqttListens;

use App\Events\MqttEvents\RegisterEvent;
use App\Model\DevicesModel;
use App\Model\UsersModel;
use Simps\DB\BaseModel;
use Simps\DB\PDO;
use Simps\Server\Protocol\MQTT;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\Context;
use Utils\Helper;
use Utils\Message;

class RegisterSubscript extends BaseModel implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            RegisterEvent::NAME => 'handle'
        ];
    }

    public function handle(RegisterEvent $event)
    {
        $hasSuccess = (new DevicesModel($event->fd))->addDeviceOrUpdate();
        $server = Context::getServer($event->fd)->res;
        // 发送注册响应结果给设备
        $c = $hasSuccess->isError ? Helper::RES_FAIL : Helper::RES_SUCCESS;
        $c = Helper::fResContent($event->fd, $c);
        $regisgerMsg = Message::getRegister($event->fd)->res;
        $regisgerMsg['content'] = $c;
        $server->send($event->fd, MQTT::getAck($regisgerMsg));
    }
}