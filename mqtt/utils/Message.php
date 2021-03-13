<?php
/**
 * 报文处理工具类
 * @package Utils
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace Utils;

use function Symfony\Component\String\u;

class Message
{
    static private $_connectKey = 'connect';

    /**
     * 从报文中获取指令
     * @return ReportFormat
     */
    static public function getCommand(int $fd): ReportFormat
    {
        $returnData = new ReportFormat();
        $hasContent = self::getCurrentContent($fd);
        if ($hasContent->isError) return $returnData;
        $content = $hasContent->res;
        if (array_key_exists('command', $content)) {
            $returnData->res = $content['command'];
            $returnData->isError = false;
        }
        return $returnData;
    }

    static public function isJson(string $isJsonString): bool
    {
        json_decode($isJsonString);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * 获取消息内容字段
     * @return ReportFormat
     */
    static public function getCurrentContent(int $fd): ReportFormat
    {
        $returnData = new ReportFormat();
        $hasData = Context::getData($fd);
        if ($hasData->isError) return $returnData;
        $data = $hasData->res;
        $content = $data['content'];
        if (!self::isJson($content)) {
            preg_match('/[^{]+(.+)/', $content, $res);
            if (!$res[1]) return $returnData;
            $content = str_replace('\"', '"', $res[1]);
        } else {
            $content = str_replace('\"', '"', $content);
        }
        $content = json_decode($content, true);
        $returnData->isError = false;
        $returnData->res = $content;
        return $returnData;
    }

    /**
     *  获取当前消息
     * @return ReportFormat
     */
    static public function getCurrent(): ReportFormat
    {
        return Context::getData();
    }

    /**
     *  保存连接消息
     */
    static public function setConnectMsg(int $fd, array $data): void
    {
        Context::set($fd, self::$_connectKey, $data);
    }

    /**
     *  获取连接消息
     * @return ReportFormat
     */
    static public function getConnectMsg(int $fd): ReportFormat
    {
        $res = Context::get($fd, self::$_connectKey);
        return $res;
    }
}