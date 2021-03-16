<?php
/**
 * 路由解析器
 * @package Utils
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace Utils;

use App\Contracts\RouteParserContract;
use App\Contracts\ValidationContract;
use App\Dispatcher;
use App\Events\WebsocketEvents\RegisterEvent;
use App\Exceptions\WsExceptions\BaseException;
use App\Exceptions\WsExceptions\RequestFormatException;

class WsRouteParser implements RouteParserContract
{
    /**
     * @param string $method
     * @param string $url
     * @param $event
     * @param ValidationContract ...$validates
     * @return array
     */
    static private function _base(string $method, string $url, $event, array $validates): array
    {
        return [
            'method' =>  strtoupper($method),
            'url' => $url,
            'event' => $event,
            'validates' => $validates
        ];
    }

    /**
     *  GET 请求
     * @param string $url
     * @param $event
     * @param ValidationContract ...$validates
     * @return array
     */
    static public function get(string $url, $event, array $validates = []): array
    {
        return self::_base('GET', $url, $event, $validates);
    }

    /**
     *  post 请求
     * @param string $url
     * @param $event
     * @param ValidationContract ...$validates
     * @return array
     */
    static public function post(string $url, $event, array $validates = []): array
    {
        return self::_base('POST', $url, $event, $validates);
    }

    /**
     *  post 请求
     * @param string $url
     * @param $event
     * @param ValidationContract ...$validates
     * @return array
     */
    static public function put(string $url, $event, array $validates = []): array
    {
        return self::_base('PUT', $url, $event, $validates);
    }

    /**
     *  post 请求
     * @param string $url
     * @param $event
     * @param ValidationContract ...$validates
     * @return array
     */
    static public function delete(string $url, $event, array $validates = []): array
    {
        return self::_base('DELETE', $url, $event, $validates);
    }


    /**
     *  patch 请求
     * @param string $url
     * @param $event
     * @param ValidationContract ...$validates
     * @return array
     */
    static public function patch(string $url, $event, array $validates = []): array
    {
        return self::_base('PATCH', $url, $event, $validates);
    }

    /**
     *  解析路由
     * @param int $fd
     * @param mixed $currentRoute
     * @param array[] $routes
     * @return ReportFormat
     */
    static public function run(int $fd, $currentRoute, array $routes): ReportFormat
    {
        $res = new ReportFormat();
        if (!self::isRouteFormat($currentRoute)) return $res;
        if (Helper::isJson($currentRoute)) $currentRoute = json_decode($currentRoute, true);
        $method = strtoupper($currentRoute['method']);
        $url = $currentRoute['url'];
        foreach ($routes as $route) {
            if ($route['method'] === $method && $route['url'] === $url) {
                $event = new $route['event']($fd, $route['method'], $route['url']);
                // 保存各种事件的消息，降低数据覆盖的可能性
                WsMessage::setMsgByEvent($event);
                // 验证
                foreach ($route['validates'] as $validation) {
                    $hasOk = (new $validation)->goCheck($event);
                    if ($hasOk->isError)  {
                        return $res;
                    }
                }
                // 发布事件
                Dispatcher::getInstance()->dispatch($event, $event::NAME);
                $res->isError = false;
                $res->res = $route;
                return $res;
            }
        }
        return $res;
    }

    /**
     * 是不是路由格式
     */
    static public function isRouteFormat($data): bool
    {
        $checkData = function ($data): bool {
            if (!array_key_exists('url', $data)) return false;
            if (!array_key_exists('method', $data)) return false;
            return true;
        };
        if (is_array($data)) {
            return $checkData($data);
        } else if(Helper::isJson($data)) {
            $data = json_decode($data, true);
            $isOk = $checkData($data);
            return $isOk;
        } else {
            throw new RequestFormatException();
        }
    }
}