<?php
/**
 * wetsocket 路由配置
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
    ShowMyDevicesEvent,
    UploadDeviceFileEvent,
    ShowDevicefilesEvent,
    DestroyDeviceFileEvent,
    UpdateDeviceFileEvent
};

use \App\Validations\WsValidations\{
    AuthValidation,
    RegisterValidation,
    LoginValidation,
    UserDeviceMustBeExistsValidation,
    UploadFileValidation,
    DeviceFileMustBeExistsValidation
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
    Router::get('/me/devices/:id', ShowDeviceDetailEvent::class, [ AuthValidation::class, UserDeviceMustBeExistsValidation::class ]),
    // 设备文件上传
    Router::post('/me/devices/:id/files', UploadDeviceFileEvent::class, [AuthValidation::class, UploadFileValidation::class, UserDeviceMustBeExistsValidation::class]),
    // 展示设备文件
    Router::get('/me/devices/:id/files', ShowDevicefilesEvent::class, [AuthValidation::class, UserDeviceMustBeExistsValidation::class]),
    // 删除设备文件
    Router::delete('/me/devices/:id/files/:fileId', DestroyDeviceFileEvent::class, [
        AuthValidation::class,
        UserDeviceMustBeExistsValidation::class,
        DeviceFileMustBeExistsValidation::class
    ]),
    // 更新设备文件
    Router::patch('/me/devices/:id/files', UpdateDeviceFileEvent::class, [
        AuthValidation::class,
        UserDeviceMustBeExistsValidation::class,
    ]),
];
