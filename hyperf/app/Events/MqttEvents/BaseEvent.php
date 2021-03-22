<?php
/**
 * Class BaseEvent
 * @package App\Events\MqttEvents
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Events\MqttEvents;

use Codedungeon\PHPCliColors\Color;

class BaseEvent
{
    public $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
}