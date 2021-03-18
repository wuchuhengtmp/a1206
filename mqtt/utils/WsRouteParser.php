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
use App\Exceptions\WsExceptions\FrontEndException;

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
        $methods = [ 'GET', 'POST', 'DELETE', 'PUT', 'PATCH' ];
        $url = $currentRoute['url'];
        if (!in_array($method, $methods)) {
            $e = new FrontEndException('the method was invalid');
            $e->method = $method;
            $e->url = $url;
            throw $e;
        }
        foreach ($routes as $route) {
            $hasMatcheUrl = self::parseUrlParams($route['url'], $url);
            if ($route['method'] === $method && !$hasMatcheUrl->isError) {
                $event = new $route['event']($fd, $route['method'], $route['url']);
                $event->routeParams = $hasMatcheUrl->res;
                // 保存各种事件的消息，降低数据覆盖的可能性
                WsMessage::setMsgByEvent($event);
                // 验证
                foreach ($route['validates'] as $validation) {
                   (new $validation)->goCheck($event);
                }
                // 发布事件
                Dispatcher::getInstance()->dispatch($event, $event::NAME);
                $res->isError = false;
                $res->res = $route;
                return $res;
            }
        }
        $e = new FrontEndException('the route wasn\'t matched');
        $e->method = strtoupper($currentRoute['method']);
        $e->url = $currentRoute['url'];
        throw $e;
        return $res;
    }

    /**
     * 是不是路由格式
     */
    static public function isRouteFormat($data): bool
    {
        $checkData = function ($data): bool {
            if (!array_key_exists('url', $data)) {
                throw new FrontEndException('url字段不能为空');
            }
            if (!array_key_exists('method', $data)) {
                throw new FrontEndException('method字段不能为空');
            }
            return true;
        };
        if (is_array($data) && !$checkData($data)) {
            throw new FrontEndException('数据格式不正确');
        }
        if(is_string($data) && !Helper::isJson($data)) {
            throw new FrontEndException('不是正确的json格式');
        } else if (is_string($data) && Helper::isJson($data) && !$checkData(json_decode($data, true)) )  {
            throw new FrontEndException('数据格式不正确');
        }
        return true;
    }

    /**
     *  匹配并解析路由参数
     * @param $route
     * @param $url
     * @return ReportFormat
     */
    static public function parseUrlParams($route, $url): ReportFormat
    {
        $report = new ReportFormat();
        preg_match_all('/:(\w+)/', $route, $res);
        $preRule= str_replace('/', '\/', $route);
        foreach ($res[0] as $placeHolder) {
            $preRule = str_replace($placeHolder, '([^\/]+)', $preRule);
        }
        $isOk = preg_match("/^$preRule$/", $url, $r);
        if ($isOk) {
            $routeParams = [];
            foreach ($res[1] as $i => $paramName) {
                $routeParams[$paramName] = $r[$i + 1];
            }
            $report->isError = false;
            $report->res = $routeParams;
        }
        return $report;
    }

}