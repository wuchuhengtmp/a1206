<?php
/**
 * Class BaseValidation
 * @package App\Validations\WsValidations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

use App\Contracts\ValidationContract;
use App\Events\WebsocketEvents\BaseEvent;
use App\Exception\WsExceptions\UserException;
use Utils\ReportFormat;
use Utils\WsMessage;

class BaseValidation implements ValidationContract
{

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [];
    }

    /**
     * 验证
     * @param BaseEvent $event
     * @return ReportFormat
     */
    public function goCheck(BaseEvent $event): void
    {
        $fields = $this->getRules();
        $messages = $this->getMessages();
        foreach ($fields as $field => $rules) {
            foreach ($rules as $rule) {
                $methodName = "_" . $rule;
                // 基本验证方法
                if (method_exists(self::class, $methodName)) {
                    $messageKey = $field . "." . $rule;
                    $message = array_key_exists($messageKey, $messages) ? $messages[$messageKey] : '';
                    $this->$methodName($event, $field, $message);
                } else if (method_exists(static::class, $rule)) {
                    // 扩展验证方法
                    $data = WsMessage::getMsgByEvent($event)->res['data'];
                    (new static())->$rule($event, $data, function ($errorMsg = '') use ($field, $rule, $event) {
                        // 错误消息
                        if ($errorMsg === '') {
                            $messages = $this->getMessages();
                            $mk = sprintf("%s.%s", $field, $rule);
                            $errorMsg = array_key_exists($mk, $messages) ? $messages[$mk] : 'the validation was faild by function' . $rule;
                        }
                        $e = new UserException($errorMsg);
                        $e->url = $event->url;
                        $e->method = $event->method;
                        throw $e;
                    });
                }
            }
        }
    }

    /**
     *  不能为空
     * @param BaseEvent $event
     * @param string $field
     */
    static private function _required(BaseEvent $event, string $field, string $message = ''): void
    {
        $res = WsMessage::getMsgByEvent($event)->res;
        if (!array_key_exists('data', $res)) {
            $e = new UserException("data 字段不能为空");
            $e->url = $event->url;
            $e->method = $event->method;
            throw $e;
        }
        $data = WsMessage::getMsgByEvent($event)->res['data'];
        if (!array_key_exists($field, $data) || strlen($data[$field]) === 0) {
            $message = $message === '' ? $field . '不能为空' : $message;
            $e = new UserException($message);
            $e->url = $event->url;
            $e->method = $event->method;
            throw $e;
        }
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return [];
    }
}