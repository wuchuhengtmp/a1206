<?php
/**
 * Class LoggedSubscript
 * @package App\Listens\MqttListens
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Listens\MqttListens;

use App\Events\MqttEvents\LoggedEvent;
use Simps\DB\BaseModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Utils\Context;

class LoggedSubscript extends BaseModel implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {

        return [
            LoggedEvent::NAME => 'handle',
        ];
    }

    public function handle(): void
    {
        $data = Context::getData();
    }
}