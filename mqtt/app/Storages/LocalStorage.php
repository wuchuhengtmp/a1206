<?php
/**
 * 本地硬盘实现
 * @package App\Storages
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Storages;

use App\Contracts\StorageContract;

class LocalStorage implements StorageContract
{
    public $defaultDisk;

    public function __construct()
    {
        $this->defaultDisk = config('filesystems')['default'];
    }

    /**
     * @param string $disk
     * @return static
     */
    public function disk(string $disk): StorageContract
    {
        $instance = new static();
        $instance->defaultDisk = $disk;
        return $instance;
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
        $fileName = sprintf('%s/%s/%s', BASE_PATH,  $c['root'], $path);
        !is_dir(dirname($fileName)) && mkdir(dirname($fileName), 0755, true);
        file_put_contents($fileName, $content);
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
        $url = sprintf('%s/%s', $c['host'], $path);
        return $url;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return config('filesystems')[$this->defaultDisk];
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        if (method_exists(self::class, $name)) {
            return (new self())->$name(...$arguments);
        }
    }
}