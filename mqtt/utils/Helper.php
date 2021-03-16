<?php
/**
 * Class Helper
 * @package Utils
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace Utils;

class Helper
{
    /**
     * 成功响应内容
     */
    const RES_SUCCESS = ['retcode' => 0];

    /**
     * 失败响应内容
     */
    const RES_FAIL = ['retcode' => -1];

    /**
     *  格式化字符串长度不足补空格
     * @param string $str
     * @param int $len
     * @return string
     */
    static public function sprinstfLen(string $str, int $len): string
    {
        if (strlen($str) >= $len) return $str;
        $times = $len - strlen($str);
        $spaces = '';
        for($i = 1; $i <= $times; $i++) {
            $spaces .= ' ';
        }
        return sprintf("%s%s", $str, $spaces);
    }

    /**
     *  解消息的内容
     */
    static public function parse(string $str): Array
    {

    }

    /**
     *  是不是JSON 字符串
     * @param string $isJsonString
     * @return bool
     */
    static public function isJson(string $isJsonString): bool
    {
        json_decode($isJsonString);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * 解析报文的内容
     *
     */
    static public function parseContent(string $content): ReportFormat
    {
        $returnData = new ReportFormat();
        if (!Helper::isJson($content)) {
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
     *  格式化响应报文的content字段
     */
    static public function fResContent(int $fd, array $content): string
    {
        $hasContent = Message::getRegisterContent($fd);
        if ($hasContent->isError) return json_encode([]);
        $newContent = $hasContent->res;
        $newContent['msgid'] = $newContent['deviceid'] . time();
        $newContent['content'] = $content;
        $content = json_encode($newContent);
        return sprintf("%04d", strlen($content)) . 'XCWL' . $content;
    }
}