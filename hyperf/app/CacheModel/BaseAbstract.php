<?php
/**
 * Class BaseAbstract
 * @package App\CacheModel
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\CacheModel;

abstract class BaseAbstract
{
    public $prefix = 'prefix';

    abstract public function set(string $key, $value): bool;

    abstract public function get(string $key);

    abstract public function has(string $key): bool;
}