<?php
/**
 * Class ShowDeviceDetailValidation
 * @package App\Validations\WsValidations
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Validations\WsValidations;

use App\Events\WebsocketEvents\BaseEvent;
use App\Model\UsersModel;
use Utils\JWT;

class ShowDeviceDetailValidation extends BaseValidation
{
    public function goCheck(BaseEvent $event): void
    {
        $auth = JWT::getAuthByEvent($event);
//        （new UsersModel($event->fd))->
        var_dump($event->routeParams);
    }
}