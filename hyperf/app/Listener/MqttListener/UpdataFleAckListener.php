<?php

/**
 *  设备文件curd回复
 */
declare(strict_types=1);

namespace App\Listener\MqttListener;

use App\CacheModel\RedisCasheModel;
use App\Events\MqttEvents\BaseEvent;
use App\Events\MqttEvents\UpdataFileAckEvent;
use App\Model\DeviceFilesModel;
use App\Model\DevicesModel;
use App\Model\UsersModel;
use App\Servics\WebsocketBroad2User;
use http\Client\Curl\User;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Utils\Helper;
use Utils\Message;
use Utils\WsMessage;
use App\Servics\SendCreateFileCommandToDevice;

/**
 * @Listener
 */
class UpdataFleAckListener implements ListenerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function listen(): array
    {
        return [
            UpdataFileAckEvent::class
        ];
    }

    public function process(object $event)
    {
        $data = $event->data;
        $payload = Helper::decodeMsgByStr($data['payload']);
        $user = ApplicationContext::getContainer()->get(UsersModel::class)->getUserByDeviceId($payload['deviceid']);
        $msgid = $payload['msgid'];
        $devcieId = $payload['deviceid'];
        $redisModel = ApplicationContext::getContainer()->get(RedisCasheModel::class);
        $fullMessage = $redisModel->getControllerMessage($devcieId, (int) $msgid);
        $url = $fullMessage['url'];
        $method = $fullMessage['method'];
        $content = $payload['content'];
        $e = new \App\Events\WebsocketEvents\BaseEvent(0, $method, $url);

        // 文件操作失败
        if ($content['recode'] != 0) {
            $recodeMapTitle = [
                '-1' => '文件挂载失败',
                '-2' => '文件打开失败',
                '-3' => '文件删除失败',
                '-4' => '文件下载任务创建失败'
            ];
            $key = "{$content['recode']}";
            $errMsg = key_exists($key, $recodeMapTitle) ? $recodeMapTitle[$key] : "未知设备发生了错误";
            // 广播失败的消息给当前用户所有连接
            ApplicationContext::getContainer()
                ->get(WebsocketBroad2User::class)
                ->sendErrorMsg($e, ['errorCode' => WsMessage::BACK_END_ERROR_CODE, 'errorMsg' => $errMsg], $msgid, $user->id );
        } else {
            // 文件操作成功
            $sourceMsg = Helper::decodeMsgByStr($fullMessage['message']);
            $device = DevicesModel::where('device_id', $devcieId)->first();
            $fileId = $fullMessage['fileId'];
            $name = $fullMessage['name'];
            switch ($sourceMsg['content']['op_mode'])
            {
                // 设备添加文件成功
                case 1:
                    $df = new DeviceFilesModel();
                    $df->device_id = $device->id;
                    $df->file_id = $fileId;
                    $df->name = $name;
                    $df->save();
                    break;
                // 设备删除文件成功
                case 2:
                    DeviceFilesModel::query()
                        ->where('device_id', $device->id)
                        ->where('id', $fileId)
                        ->delete();
                    break;
            }
            $newDeviceFiles = (new DevicesModel())->getFilesByDeviceId($device->id);
            // 把最新的文件列表广播给当前用户
            ApplicationContext::getContainer()
                ->get(WebsocketBroad2User::class)
                ->sendSuccessMsg($e, $newDeviceFiles, $msgid, $user->id);
            // 出栈已成功上传的文件
            $redisModel->shiftUploadFile2Queue($devcieId);
            // 如果还有文件在队列中，则接着发送
            $redisModel->tagDeviceQueueToFree($devcieId);
            count($redisModel->getUploadFileQueueByDeviceId($devcieId)) > 0
            && (new SendCreateFileCommandToDevice())->sendFileFormQueueByDeviceId($devcieId);

        }
    }
}
