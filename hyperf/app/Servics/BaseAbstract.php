<?php
/**
 * Class BaseAbstract
 * @package App\Servics
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Servics;

use App\Events\WebsocketEvents\BaseEvent;

abstract class BaseAbstract
{
    abstract public function send(BaseEvent $event, $content = null): void;
}