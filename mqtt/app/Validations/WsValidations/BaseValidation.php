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
use App\Exceptions\WsExceptions\UserException;
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
                if (method_exists($this, $methodName)) {
                    $messageKey = $field . "." . $rule;
                    $message = array_key_exists($messageKey, $messages) ? $messages[$messageKey] : '';
                    $this->$methodName($event, $field, $message);
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
        $data = WsMessage::getMsgByEvent($event)->res['data'];
        if (!array_key_exists($field, $data) || strlen($data[$field]) === 0) {
            $message = $message === '' ? $field . '不能为空' : $message;
            throw new UserException($message);
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