<?php
/**
 * JWT 实现约束
 * @package App\Contracts
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Contracts;

interface JWTContract
{
    /**
     *  token 生成
     * @param int $uid
     * @return string
     */
    static public function generate(int $uid): string;

    /** 签名验证
     * @param string $signing
     * @return bool
     */
    static public function check(string $signing): bool;
}