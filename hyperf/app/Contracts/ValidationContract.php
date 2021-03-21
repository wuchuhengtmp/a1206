<?php
/**
 * 验证器约束
 * @package App\Contracts
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Contracts;

use App\Events\WebsocketEvents\BaseEvent;
use Utils\ReportFormat;

interface ValidationContract
{
    public function getRules(): array;

    public function goCheck(BaseEvent $event): void;

    public function getMessages(): array;
}