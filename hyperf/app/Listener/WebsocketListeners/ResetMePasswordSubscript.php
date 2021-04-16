<?php
/**
 * Class CreateSmsCodeSubscript
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\CacheModel\RedisCasheModel;
use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\ResetMePasswordEvent;
use App\Model\UsersModel;
use Hyperf\Utils\ApplicationContext;
use Overtrue\EasySms\EasySms;
use App\Events\WebsocketEvents\CreateMsmCodeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\Encrypt;
use Utils\Helper;
use Utils\WsMessage;
use function _HumbugBoxa9bfddcdef37\RingCentral\Psr7\str;

class ResetMePasswordSubscript implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ResetMePasswordEvent::NAME => 'handle'
        ];
    }

    public function handle(BaseEvent $event)
    {
        $data = WsMessage::getMsgByEvent($event)->res['data'];
        $redisModel = ApplicationContext::getContainer()->get(RedisCasheModel::class);
        $key = $data['access_key'];
        $codeInfo = $redisModel->getMsgInfo($key);
        $phone = $codeInfo['phone'];
        $user = UsersModel::where('username', $phone)->first();
        $user->password = Encrypt::hash($data['password']);
        if ($user->save()) {
            $redisModel->delMsgInfoByKey($key);
            WsMessage::resSuccess($event );
        } else {
            WsMessage::resError($event, ['errorMsg' => '密码重置失败']);
        }
    }
}