<?php
/**
 * 七牛硬盘
 * @package App\Storages
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Storages;

use App\Contracts\StorageContract;
use Qiniu\Storage\UploadManager;
use Qiniu\Auth;

class QiniuStoreage implements StorageContract
{

    /**
     * @param string $disk
     * @return $this
     */
    public function disk(string $disk): StorageContract
    {
        // TODO: Implement disk() method.
    }

    /**
     * @param string $fileName
     * @param string $content
     * @return string
     */
    public function put(string $fileName, string $content): string
    {
        $c = $this->getConfig();
        $path = sprintf('%s/%s', $c['prefixDir'], $fileName);
        $uploadMgr = new UploadManager();
        $auth = new Auth($c['accessKey'], $c['secretKey']);
        $token = $auth->uploadToken($c['bucket']);
        // :xxx todo 这里需要异常处理下
        list($ret, $error) = $uploadMgr->putFile($token, $path, '/Users/wuchuheng/Desktop/myProject/a1206/com.huizhouyiren.a1206Admin/mqtt/storages/files/localDisk/index.txt');
        return $path;
    }

    /**
     * @param string $path
     * @return string
     */
    public function get(string $path): string
    {
        // TODO: Implement get() method.
    }

    /**
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool
    {
        // TODO: Implement exists() method.
    }

    /**
     * @param string $path
     * @return string
     */
    public function url(string $path): string
    {
        $c = $this->getConfig();
        return sprintf('%s/%s', $c['host'], $path);
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return config('filesystems')['qiniu'];
    }
}