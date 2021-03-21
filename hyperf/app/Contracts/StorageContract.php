<?php
/**
 * 文件系统契约
 * @package App\Contracts
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Contracts;

interface StorageContract
{
    public function disk(string $disk): self;

    /**
     * 保存文件
     * @param string $fileName
     * @param string $content
     * @return string
     */
    public function put(string $fileName, string $content): string;

    /**
     * @param string $path
     * @return string
     */
    public function get(string $path): string;

    /**
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool;

    /**
     * @param string $path
     * @return string
     */
    public function url(string $path): string;

    /**
     * @return array
     */
    public function getConfig(): array;
}