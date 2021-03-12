<?php
/**
 * mqtt事件接口契约
 * @description 用于限制事件类的功能
 * @author wuchuheng  <wuchuheng@163.com>
 */

namespace App\Contracts;

interface MqttEventInterface
{
    public function __construct($server, int $fd, $fromId, $data);

    public function getServer(): ?Object;

    public function getFd(): int;

    public function getFromId(): ?int;

    public function getData();
}