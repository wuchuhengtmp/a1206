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
              `device_id` varchar(255) NOT NULL COMMENT '设备ID',
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
              `client_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'mqtt设备id',
              `clean_session` int(1) NOT NULL COMMENT '1是0否',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
