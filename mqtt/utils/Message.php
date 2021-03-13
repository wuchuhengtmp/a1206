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
    /**
     * 从报文中获取指令
     * @return ReportFormat
     */
    static public function getCommand(): ReportFormat
    {
        $data = Context::getData();
        $content = $data['content'];
        $returnData = new ReportFormat();
        if (!self::isJson($content)) {
            preg_match('/[^{]+(.+)/', $content, $res);
            if (!$res[1]) return $returnData;
            $content = str_replace('\"', '"', $res[1]);
        } else {
            $content = str_replace('\"', '"', $content);
        }
        $content = json_decode($content, true);
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
}