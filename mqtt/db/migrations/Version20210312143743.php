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
              `device_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '设备ID',
              `user_id` int(11) NOT NULL COMMENT '用户ID',
              `ip_address` varchar(39) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '设备ip',
              `keepalive` int(11) NOT NULL COMMENT '心跳',
              `protocol` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '连接协议',
              `status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'online 或 offline',
              `vender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '厂商代码',
              `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
              `last_ack_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后通信',
              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
              `connected_at` timestamp NOT NULL COMMENT '连接时间',
              `client_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'mqtt设备id',
              `clean_session` int(1) NOT NULL COMMENT '1是0否',
              `play_state` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '播放状态定义：系统上电处于停止状态00(停止)01(播放)02(暂停)',
              `play_mode` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(00)按顺序播放全盘曲目,播放完后循环播放单曲循环 (01)一直循环播放当前曲目单曲停止 (02)播放完当前曲目一次停止全盘随机(03)随机播放盘符内曲目目录循环 (04)按顺序播放当前文件夹内曲目,播放完后循环播放 (05)在当前目录内随机播放，目录不包含子目录目录顺序播放 (06)按顺序播放当前文件夹内曲目,播放完后停止 (07)按顺序播放全盘曲目,播放完后停止\n',
              `play_sound` int(2) DEFAULT NULL COMMENT '0~30 对应31级音量',
              `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '设备别名',
              `category_id` int(10) NOT NULL DEFAULT '1' COMMENT '分类id',
              `file_cnt` int(255) DEFAULT NULL COMMENT '总曲目数',
              `file_current` int(255) DEFAULT NULL COMMENT '当前曲目',
              `play_timer_sum` int(11) DEFAULT NULL COMMENT '总曲目播放时间',
              `play_timer_cur` int(11) DEFAULT NULL COMMENT '当前曲目播放时间',
              `memory_size` int(11) DEFAULT NULL COMMENT '内存余量',
              `trigger_modes` varbinary(255) DEFAULT NULL,
              `battery_vol` int(255) DEFAULT NULL COMMENT '电力',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
MySQL_QUERY
        );
    }

    public function down(Schema $schema) : void
    {
        if ($schema->hasTable($this->tableName)) {
            $schema->dropTable($this->tableName);
        }
    }

    public function postUp(Schema $schema): void
    {
        $sql = "INSERT INTO `devices`(`id`, `device_id`, `user_id`, `ip_address`, `keepalive`, `protocol`, `status`, `vender`, `version`, `last_ack_at`, `created_at`, `connected_at`, `client_id`, `clean_session`, `play_state`, `play_mode`, `play_sound`, `alias`, `category_id`, `file_cnt`, `file_current`, `play_timer_sum`, `play_timer_cur`, `memory_size`, `trigger_modes`, `battery_vol`) VALUES (3, '868739052017831', 1, '192.168.0.41', 90, 'MQTT', 'online', 'XCWL', 'JRBJQ_AIR724_V01_01', '2021-03-18 10:56:12', '2021-03-18 10:56:12', '0000-00-00 00:00:00', '868739052017831', 1, '1', '0', 30, '868739052017831', 1, 33686018, 0, 16, 16, -1869574000, 0x5B7B226368616E656C223A302C226D6F6465223A307D2C7B226368616E656C223A312C226D6F6465223A307D2C7B226368616E656C223A322C226D6F6465223A307D2C7B226368616E656C223A332C226D6F6465223A307D5D, 0);";
        $this->connection->exec($sql);
        parent::postUp($schema);
    }
}
