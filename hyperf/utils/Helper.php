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
use App\Model\ConfigsModel;

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
     * 格式化为符合设备的消息格式
     * @param array $content
     * @return string
     */
    static public function fMqttMsg(array $content): string
    {
        $content = \json_encode($content, JSON_UNESCAPED_SLASHES);
        return sprintf('%04d', strlen($content)) . 'XCWL' . $content;
    }

    /**
     *  获取设备订阅的主题
     * @param string $deviceId
     * @return string
     */
    static public function formatTopicByDeviceId(string $deviceId): string
    {
        return sprintf("JRBJQ_AIR724_%s", $deviceId);
    }

    /**
     *  解析消息
     * @param string $payload
     * @return array
     */
    static public function decodeMsgByStr(string $payload): array
    {
        return json_decode(substr($payload, 8), true);
    }

    static public function getConfByKey(string $key): string {
        try {
            $val = ConfigsModel::query()->where('name', $key)->first()->value;
        }catch (\Exception $e) {
            $pdo = self::getPdoInstance();
            $sql = "SELECT `value` from configs WHERE `name` = '{$key}' LIMIT 1";
            $prepare = $pdo->prepare($sql);
            $prepare->execute();
            $res = $prepare->fetch();
            $val = $res['value'];

        }
        return $val;
    }

    static public function randStr($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    protected static $_dbh = null;

    static public function getPdoInstance(): \PDO {
        if (self::$_dbh === null) {
            $db_host = env('HYPERF_DB_HOST');
            $db_user = env('HYPERF_DB_USERNAME');
            $db_password = env('HYPERF_DB_PASSWORD');
            $db = env('HYPERF_DB_DATABASE');
            $port = env('HYPERF_DB_PORT');
            self::$_dbh = new \PDO("mysql:host=$db_host;dbname=$db;port=$port", $db_user, $db_password);
        }
        return self::$_dbh;
    }
}
