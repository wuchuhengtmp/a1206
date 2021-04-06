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
use Hyperf\DbConnection\Db;
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
                $withPropertyMethodName = explode(':', $rule)[0];
                // 基本验证规则
                if (method_exists(self::class, $methodName)) {
                    $messageKey = $field . "." . $rule;
                    $message = array_key_exists($messageKey, $messages) ? $messages[$messageKey] : '';
                    $this->$methodName($event, $field, $message, $rules);
                } else if (method_exists(self::class, '_' . $withPropertyMethodName)) {
                    // 带有参数规则内置规则
                    $messageKey = $field . "." . $withPropertyMethodName;
                    $message = array_key_exists($messageKey, $messages) ? $messages[$messageKey] : '';
                    $methodName ='_' . $withPropertyMethodName;
                    $params = explode(':', $rule)[1];
                    $this->$methodName($event, $field, $message, $params);
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
                        $e->event = $event;
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
     *  集合验证
     * @param BaseEvent $event
     * @param string $field
     * @param string $message
     */
    static private function _in(BaseEvent $event, string $field, string $message = '', string $params): void
    {
        $res = WsMessage::getMsgByEvent($event)->res;
        if (array_key_exists($field, $res['data'])) {
            $newParams  = explode(',', $params);
            if (!array_key_exists((string) $res['data'][$field], $newParams)) {
                $message = $message === '' ? $field . '必须是集合元素 ' . $params . ' 中的1个'  : $message;
                $e = new UserException($message);
                $e->url = $event->url;
                $e->method = $event->method;
                throw $e;
            }
        }
    }

    /**
     *  区间验证
     * @param BaseEvent $event
     * @param string $field
     * @param string $message
     * @param string $params
     */
    static private function _between(BaseEvent $event, string $field, string $message = '', string $params): void
    {
        $res = WsMessage::getMsgByEvent($event)->res;
        if (array_key_exists($field, $res['data'])) {
            list($limtStart, $limitEnd) = explode(',', $params);
            if ($res['data'][$field] >= $limtStart &&  $res['data'][$field] <= $limitEnd) {
            } else {
                $message = $message === '' ? $field . ' 必须是在 ' . $params . ' 之间'  : $message;
                $e = new UserException($message);
                $e->url = $event->url;
                $e->method = $event->method;
                throw $e;
            }
        }
    }

    /**
     *  表有这个数据
     * @param BaseEvent $event
     * @param string $field
     * @param string $message
     * @param string $params
     */
    static private function _exists(BaseEvent $event, string $field, string $message = '', string $params): void
    {
         $data = WsMessage::getMsgByEvent($event)->res;
        if (array_key_exists('data', $data) && array_key_exists($field, $data['data'])) {
            $value = $data['data'][$field];
            $params = explode(',', $params);
            list($model, $tableField) = count($params) === 2 ? $params : [$params[0], 'id'];
            $isExists = class_exists($model) ?
                (new $model())->where($tableField, $value)->get()->isNotEmpty() :
                Db::table($model)->where($tableField, $field)->get()->isNotEmpty();
            if (!$isExists) {
                $message = $message === '' ? $field . ' 没有这个 ' . $params[0] . ' 参数' : $message;
                $e = new UserException($message);
                $e->url = $event->url;
                $e->method = $event->method;
                throw $e;
            }
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