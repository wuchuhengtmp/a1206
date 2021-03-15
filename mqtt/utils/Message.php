<?php
/**
 * 报文处理工具类
 * @package Utils
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace Utils;

use Codedungeon\PHPCliColors\Color;
use function Symfony\Component\String\u;

class Message
{
    private const REGISTER_KEY = 'register'; // 缓存注册指令消息的key

    private const CONNECT_KEY = 'connect';  // 缓存连接指令消息的key

    private const SUBSCRIPT_KEY = 'subscript'; // 缓存订阅数据

    private const DISCONNECT_DEVICE_ID = 'disconnect_id'; // 断开fd

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
        $hasContent = Helper::parseContent($data['content']);
        if ($hasContent->isError) return $hasContent;
        $content = $hasContent->res;
        $returnData->isError = false;
        $returnData->res = $content;
        return $returnData;
    }

    /**
     *  获取当前消息
     * @return ReportFormat
     */
    static public function getCurrent(int $fd): ReportFormat
    {
        return Context::getData($fd);
    }

    /**
     *  保存连接消息
     */
    static public function setConnectMsg(int $fd, array $data): void
    {
        Context::set($fd, self::CONNECT_KEY, $data);
    }

    /**
     *  获取连接消息
     * @return ReportFormat
     */
    static public function getConnectMsg(int $fd): ReportFormat
    {
        $res = Context::get($fd, self::CONNECT_KEY);
        return $res;
    }

    /**
     * 保存设备注册数据
     */
    static public function setRegister(int $fd, array $data): void
    {
        Context::set($fd, self::REGISTER_KEY, $data);
    }

    /**
     *  获取设备注册数据
     */
    static public function getRegister(int $fd): ReportFormat
    {
        return Context::get($fd, self::REGISTER_KEY);
    }

    /**
     *  获取注册内容
     */
    static public function getRegisterContent(int $fd): ReportFormat
    {
        $hasData = self::getRegister($fd);
        if ($hasData->isError) return $hasData;
        $data = $hasData->res;
        $hasContent = Helper::parseContent($data['content']);
        return $hasContent;
    }

    /**
     * 保存订阅消息
     */
    static public function setSubscriptMsg(int $fd, array $value): void
    {
        Context::set($fd, self::SUBSCRIPT_KEY, $value);
    }

    /**
     * 获取订阅消息
     */
    static public function getSubscriptMsg(int $fd): ReportFormat
    {
        return Context::get($fd, self::SUBSCRIPT_KEY);
    }

    /**
     *  缓存断开的id
     */
    static public function setDisconnectClientId(string $deviceId): void
    {
        Context::setGlobal(self::DISCONNECT_DEVICE_ID, $deviceId);
    }

    /**
     * 获取断开的fd
     */
    static public function getDisconnectClientId(): ReportFormat
    {
        return Context::getGlobal(self::DISCONNECT_DEVICE_ID);
    }
}
