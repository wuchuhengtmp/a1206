<?php
/**
 * Class UploadSubscript
 * @package App\Listener\WebsocketListeners
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listener\WebsocketListeners;

use App\Events\WebsocketEvents\BaseEvent;
use App\Events\WebsocketEvents\UploadDeviceFileEvent;
use App\Model\FilesModel;
use App\Model\DeviceFilesModel;
use App\Model\UsersModel;
use App\Servics\SendCreateFileCommandToDevice;
use App\Storages\Storage;
use Hyperf\Utils\ApplicationContext;
use League\Flysystem\Filesystem;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\WsMessage;

class UploadDeviceFileSubscript implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            UploadDeviceFileEvent::NAME => 'handle'
        ];
    }

    public function handle(BaseEvent  $event)
    {
        $dotenv = new Dotenv();
        $dotenv->load(BASE_PATH . '/.env');
        $msg = WsMessage::getMsgByEvent($event)->res;
        $name = $msg['data']['name'];
        $fileBase64 = $msg['data']['file'];
        $fileCon = base64_decode($fileBase64);
        $defaultDisk = env('HYPERF_DEFAULT_DISK' );
        $diskInstance = ApplicationContext::getContainer()->get(\League\Flysystem\Filesystem::class);
        $path = sprintf("%s/%s%s.mp3", date('Y-m-d', time()), date('ymdhis', time()),  $name);
        $diskInstance->write($path, $fileCon);
        $file = new FilesModel();
        $file->path = $path;
        $file->disk = $defaultDisk;
        $file->save();
        $content = [
            'op_mode' => 1,
            'http_root' => $file['url'],
            'file_check_sum' => $file->size,
            'file_lenth' => $file->size,
            'del_file' => -1
        ];
        (new SendCreateFileCommandToDevice())->send($event, $content, $file->id);
    }
}