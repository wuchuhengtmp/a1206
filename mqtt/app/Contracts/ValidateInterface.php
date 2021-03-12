<?php
/**
 * Class ValidateInterface
 * @package App\Contracts
 * @author wuchuheng  <wuchuheng@163.com>
 */

declare(strict_type=1);

namespace App\Contracts;

interface ValidateInterface
{
    public function check($server, int $fd, $fromId, $data);
}