<?php
/**
 * wetsocket 路由
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

use Utils\WsRouteParser as Router;

use App\Events\WebsocketEvents\{
    ShowDeviceDetailEvent,
    LoginEvent,
    RegisterEvent,
    PingEvent,
    ShowCategoriesEvent,
    ShowMyDevicesEvent
};

use \App\Validations\WsValidations\{
    AuthValidation,
    RegisterValidation,
    LoginValidation,
    ShowDeviceDetailValidation
};
return [
    // 登录
    Router::post('/users/authorizations', LoginEvent::class, [LoginValidation::class]),
    // 注册
    Router::post('/users', RegisterEvent::class, [RegisterValidation::class]),
    // 心跳
    Router::post('/pings', PingEvent::class),
    // 分类列表
    Router::get('/categories', ShowCategoriesEvent::class),
    // 用户设备
    Router::get('/me/devices', ShowMyDevicesEvent::class, [AuthValidation::class]),
    // 用户设备详情
    Router::get('/me/devices/:id', ShowDeviceDetailEvent::class, [ AuthValidation::class, ShowDeviceDetailValidation::class ])
];
