<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use App\Events\MqttEvents\ClientConnectedEvent;
use App\Events\MqttEvents\GetDataAllAckEvent;
use App\Events\MqttEvents\PlayCrtlAckEvent;
use App\Events\MqttEvents\RegisterEvent;
use App\Events\MqttEvents\ReportDataEvent;
use http\Env\Request;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Hyperf\Di\Annotation\Inject;

class IndexController extends AbstractController
{
    /**
     * @Inject
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function index(RequestInterface $request)
    {
        $content = $request->getBody()->getContents();
        $content = json_decode($content, true);
        if (array_key_exists('payload', $content)) {
            $payload = $content['payload'];
            $payload = substr($payload, 8);
            $payload = json_decode($payload, true);

            switch ($payload['command']) {
                case 'report_data':
                    $this->eventDispatcher->dispatch(new ReportDataEvent($content));
                    break;
                    // 注册
                case 'register':
                    $this->eventDispatcher->dispatch(new RegisterEvent($content));
                    break;
                    // 获取设备数据
                case 'get_data_all_ack':
                    $this->eventDispatcher->dispatch(new GetDataAllAckEvent($content));
                    break;
                    // 控制指令回复
                case 'play_crtl_ack':
                    var_dump("hell\n");
                    $this->eventDispatcher->dispatch(new PlayCrtlAckEvent($content));
                    break;
            }
        }
        switch ($content['action']) {
            // 设备连接
            case 'client_connected':
                $this->eventDispatcher->dispatch(new ClientConnectedEvent($content));
                break;
        }

        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }
}
