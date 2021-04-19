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
use Hyperf\Utils\ApplicationContext;
use Overtrue\EasySms\EasySms;
use App\Events\WebsocketEvents\CreateMsmCodeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\Helper;
use Utils\WsMessage;
use function _HumbugBoxa9bfddcdef37\RingCentral\Psr7\str;

class CreateSmsCodeSubscript implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            CreateMsmCodeEvent::NAME => 'handle'
        ];
    }

    public function handle(BaseEvent $event)
    {
        $config = [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,

            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

                // 默认可用的发送网关
                'gateways' => [
                    'yunpian', 'aliyun', 'alidayu',
                ],
            ],
            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => '/tmp/easy-sms.log',
                ],
                'yunpian' => [
                    'api_key' => '824f0ff2f71cab52936axxxxxxxxxx',
                ],
                'aliyun' => [
                    'access_key_id' => Helper::getConfByKey('ALIYUN_SMS_ACCESS_KEY_ID'),
                    'access_key_secret' => Helper::getConfByKey('ALIYUN_SMS_ACCESS_KEY_SECRET'),
                    'sign_name' => Helper::getConfByKey('ALIYUN_SMS_SIGN_NAME'),
                ],
                'alidayu' => [
                    //...
                ],
            ],
        ];

        $easySms = new EasySms($config);

        $key = 'captcha_' . Helper::randStr(10);
        $code = sprintf('%04d',  rand(0, 9999));
        $data = WsMessage::getMsgByEvent($event)->res['data'];
        try {
            $easySms->send($data['phone'], [
                'template' => Helper::getConfByKey('ALIYUN_SMS_TEMPLATE'),
                'data' => ['code' => $code],
            ]);
            WsMessage::resSuccess($event, ['access_key' => $key]);
            ApplicationContext::getContainer()
                ->get(RedisCasheModel::class)
                ->setMsgInfo($key, ['code' => $code, 'phone' => $data['phone']]);
        } catch (\Exception $e) {
            WsMessage::resError($event, ['errorMsg' => $e->getExceptions()]);
        }
    }
}