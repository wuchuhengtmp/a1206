<?php
/**
 * Class Helper.
 */
declare(strict_types=1);
/**
 *
 * @link     https://wuchuheng.com
 * @author   wuchuheng <wuchuheng@163.com>
 * @license  MIT
 */
namespace Utils;

use App\Events\MqttEvents\BaseEvent;

class Helper
{
    /**
     * 成功响应内容.
     */
    const RES_SUCCESS = ['retcode' => 0];

    /**
     * 失败响应内容.
     */
    const RES_FAIL = ['retcode' => -1];

    /**
     *  格式化字符串长度不足补空格
     */
    public static function sprinstfLen(string $str, int $len): string
    {
        if (strlen($str) >= $len) {
            return $str;
        }
        $times = $len - strlen($str);
        $spaces = '';
        for ($i = 1; $i <= $times; ++$i) {
            $spaces .= ' ';
        }
        return sprintf('%s%s', $str, $spaces);
    }

    /**
     *  解消息的内容.
     */
    public static function parse(string $str): array
    {
    }

    /**
     *  是不是JSON 字符串.
     */
    public static function isJson(string $isJsonString): bool
    {
        json_decode($isJsonString);
        return json_last_error() == JSON_ERROR_NONE;
    }

    /**
     * 解析报文的内容.
     */
    public static function parseContent(string $content): ReportFormat
    {
        $returnData = new ReportFormat();
        if (! Helper::isJson($content)) {
            preg_match('/[^{]+(.+)/', $content, $res);
            if (! $res[1]) {
                return $returnData;
            }
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
     * @param array $content
     * @return string
     */
    static public function fMqttMsg(array $content): string
    {
        $content = \json_encode($content);

        return sprintf('%04d', strlen($content)) . 'XCWL' . $content;
    }

    static public function formatTopicByDeviceId(string $deviceId): string
    {
        return sprintf("JRBJQ_AIR724_%s", $deviceId);
    }
}
