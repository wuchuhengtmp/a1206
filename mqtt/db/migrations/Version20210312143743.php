<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210312143743 extends AbstractMigration
{
    private $tableName = 'devices';
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql(<<<MySQL_QUERY
            CREATE TABLE `{$this->tableName}` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `device_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '设备ID',
              `user_id` int(11) NOT NULL COMMENT '用户ID',
              `ip_address` varchar(39) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '设备ip',
              `keepalive` int(11) NOT NULL COMMENT '心跳',
              `protocol` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '连接协议',
              `status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'online 或 offline',
              `vender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '厂商代码',
              `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
              `lasttalked_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后通信',
              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
              `connected_at` timestamp NOT NULL COMMENT '连接时间',
              `client_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'mqtt设备id',
              `clean_session` int(1) NOT NULL COMMENT '1是0否',
              `play_state` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '播放状态定义：系统上电处于停止状态00(停止)01(播放)02(暂停)',
              `play_mode` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(00)按顺序播放全盘曲目,播放完后循环播放单曲循环 (01)一直循环播放当前曲目单曲停止 (02)播放完当前曲目一次停止全盘随机(03)随机播放盘符内曲目目录循环 (04)按顺序播放当前文件夹内曲目,播放完后循环播放 (05)在当前目录内随机播放，目录不包含子目录目录顺序播放 (06)按顺序播放当前文件夹内曲目,播放完后停止 (07)按顺序播放全盘曲目,播放完后停止\n',
              `play_sound` int(2) DEFAULT NULL COMMENT '0~30 对应31级音量',
              `alias` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '设备别名',
              `category_id` int(10) NOT NULL DEFAULT '1' COMMENT '分类id',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
MySQL_QUERY
        );
    }

    public function down(Schema $schema) : void
    {
        if ($schema->hasTable($this->tableName)) {
            $schema->dropTable($this->tableName);
        }
    }
}
