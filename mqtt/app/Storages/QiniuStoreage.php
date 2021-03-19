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
        $file = tmpfile();
        fwrite($file, $content);
        $fullPath = stream_get_meta_data($file)['uri'];
        list($ret, $error) = $uploadMgr->putFile($token, $path, $fullPath);
        fclose($file);
        return $path;
    }

    /**
     * @param string $path
     * @return string
     */
    public function get(string $path): string
    {

    }

    /**
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool
    {

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