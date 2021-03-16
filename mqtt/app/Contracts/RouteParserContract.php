<?php
/**
 * 路由解析器约束
 * @package App\Contracts
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Contracts;

use Utils\ReportFormat;

interface RouteParserContract
{
    /**
     *  get 请求
     * @param string $url
     * @param $event
     * @param array $validates
     * @return array
     */
    static public function get(string $url, $event, array $validates = []): array;

    /**
     *  post 请求
     * @param string $url
     * @param $event
     * @param array $validates
     * @return array
     */
    static public function post(string $url, $event,  array $validates = []): array;

    /**
     *  put 请求
     * @param string $url
     * @param $event
     * @param array $validates
     * @return array
     */
    static public function put(string $url, $event, array $validates = []): array;

    /**
     *  patch 请求
     * @param string $url
     * @param $event
     * @param array $validates
     * @return array
     */
    static public function patch(string $url, $event, array $validates = []): array;

    /**
     *  delete 请求
     * @param string $url
     * @param $event
     * @param array $validates
     * @return array
     */
    static public function delete(string $url, $event, array $validates = []): array;

    /**
     * 执行当前匹配的路由
     * @param int $fd
     * @param mixed $currentRoute
     * @param array ...$routes
     * @return ReportFormat
     */
    static public function run(int $fd, $currentRoute, array $routes): ReportFormat;
}