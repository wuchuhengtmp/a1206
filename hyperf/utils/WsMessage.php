<?php
/**
 * ws 消息处理
 * @package Utils
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace Utils;

use App\Events\WebsocketEvents\BaseEvent;
use App\Exception\WsExceptions\ConnectBrokenException;
use Hyperf\Utils\ApplicationContext;
use Swoole\Server;

class WsMessage
{
    /**
     *  成功响应
     * @param BaseEvent $event
     * @param array $data
     * @return ReportFormat
     */
    static public function resSuccess(BaseEvent $event, array $data = [], $msgid = null): ReportFormat
    {
        $res = new ReportFormat();
        $server = ApplicationContext::getContainer()->get(Server::class);
        $msgid = $msgid ?? self::getMsgByEvent($event)->res['msgid'];
        $server->push($event->fd, json_encode(
            [
                'url' => $event->url,
                'method' => strtoupper($event->method),
                'isSuccess' => true,
                'data' => $data,
                'msgid' => $msgid
            ]
        ));
        $res->isError = false;
        return $res;
    }

    /**
     *  错误响应
     * @param BaseEvent $event
     * @param array $data
     * @return ReportFormat
     */
    static public function resError(BaseEvent $event, array $data = []): ReportFormat
    {
        $res = new ReportFormat();
        $hasServer = Context::getServer($event->fd);
        if ($hasServer->isError) return $res;
        $server = $hasServer->res;
        $resData = [
            'url' => $event->url,
            'method' => $event->method,
            'isSuccess' => false,
            'errorCode' => array_key_exists('errorCode', $data) ? $data['errorCode'] : 1,
            'errorMsg' => array_key_exists('errorMsg', $data) ? $data['errorMsg'] : 'the action was failed'
        ];
        $server->push($event->fd, json_encode($resData));
        $res->isError = false;
        return $res;
    }

    /**
     *  保存当前事件的数据
     * @param BaseEvent $event
     * @return ReportFormat
     */
    static public function setMsgByEvent(BaseEvent $event): ReportFormat
    {
        $fd = $event->fd;
        $res = new ReportFormat();
        $hasFrame = Context::get($fd, 'frame');
        if ($hasFrame->isError) return $res;
        Context::set($fd, $event->messageId, json_decode($hasFrame->res->data,true));
        $res->isError = false;
        return $res;
    }

    /**
     *  获取事件消息
     * @param BaseEvent $event
     * @return ReportFormat
     */
    static public function getMsgByEvent(BaseEvent $event) : ReportFormat
    {
        $hasData = Context::get($event->fd, $event->messageId);
        if ($hasData->isError) {
            throw new ConnectBrokenException();
        }
        return $hasData;
    }

    /**
     * 格式化token
     * @param string $token
     * @return string[]
     */
    static public function formatJWTToken(string $token): array
    {
        return [
            'tokenType' => 'Bearer',
            'accessToken' => $token
        ];
    }
}