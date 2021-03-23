<?php
/**
 * wetsocket 路由配置
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

use Utils\WsRouteParser as Router;

use App\Events\WebsocketEvents\{
    SetDeviceSoundEvent,
    ShowDeviceDetailEvent,
    LoginEvent,
    RegisterEvent,
    PingEvent,
    ShowCategoriesEvent,
    ShowMyDevicesEvent,
    UploadDeviceFileEvent,
    ShowDevicefilesEvent,
    DestroyDeviceFileEvent,
    UpdateDeviceFileEvent,
    DevicePlayDevent,
    PlayFilesEvent
};
//
use \App\Validations\WsValidations\{
    AuthValidation,
    RegisterValidation,
    LoginValidation,
    UserDeviceMustBeExistsValidation,
    UploadFileValidation,
    DeviceFileMustBeExistsValidation,
    PlayFilesValidation
};
return [
    // 登录
    Router::post('/users/authorizations', LoginEvent::class, [LoginValidation::class] ),
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
//    // 设备文件上传
//    Router::post('/me/devices/:id/files', UploadDeviceFileEvent::class, [AuthValidation::class, UploadFileValidation::class, UserDeviceMustBeExistsValidation::class]),
//    // 展示设备文件
    Router::get('/me/devices/:id/files', ShowDevicefilesEvent::class, [AuthValidation::class, UserDeviceMustBeExistsValidation::class]),
//    // 删除设备文件
//    Router::delete('/me/devices/:id/files/:fileId', DestroyDeviceFileEvent::class, [
//        AuthValidation::class,
//        UserDeviceMustBeExistsValidation::class,
//        DeviceFileMustBeExistsValidation::class
//    ]),
//    // 更新设备文件
    Router::patch('/me/devices/:id/files', UpdateDeviceFileEvent::class, [
        AuthValidation::class,
        UserDeviceMustBeExistsValidation::class,
    ]),
    // 设备播放
    Router::put('/me/devices/:id/play', DevicePlayDevent::class, [
        AuthValidation::class,
        \App\Validations\WsValidations\DevicePlayValidation::class,
        UserDeviceMustBeExistsValidation::class,
    ]),
    // 设备设备声音
    Router::put('/me/devices/:id/sounds', SetDeviceSoundEvent::class, [
        AuthValidation::class,
        \App\Validations\WsValidations\LimitSoundValidation::class,
        UserDeviceMustBeExistsValidation::class,
    ]),
    // 上/下一曲
    Router::put('/me/devices/:id/playFiles/:status', PlayFilesEvent::class, [
        AuthValidation::class,
        PlayFilesValidation::class,
        UserDeviceMustBeExistsValidation::class,
    ])
];
