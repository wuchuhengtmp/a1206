<?php
/**
 * wetsocket 路由
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

use Utils\WsRouteParser as Router;

return [
    // 登录
    Router::post('/users/authorizations', \App\Events\WebsocketEvents\LoginEvent::class),
    // 注册
    Router::post('/users', \App\Events\WebsocketEvents\RegisterEvent::class, [\App\Validations\WsValidations\LoginValidation::class])
];
