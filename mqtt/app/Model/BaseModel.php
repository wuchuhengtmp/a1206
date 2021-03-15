<?php

declare(strict_types=1);
/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */
namespace App\Model;

use Simps\DB\BaseModel as SBaseModel;

class BaseModel extends SBaseModel
{
    public $fd;

    public function __construct(int $fd)
    {
        $this->fd = $fd;
        parent::__construct();
    }
}
