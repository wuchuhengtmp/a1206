<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210315022411 extends AbstractMigration
{
    private $tableName = 'subscriptions';

    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql(<<<MySQL_QUERY
        CREATE TABLE `{$this->tableName}` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `fd` int(11) NOT NULL COMMENT '连接id',
          `device_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '设备id',
          `topic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '主题',
          `qos` int(3) NOT NULL COMMENT '消息等级',
          `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='主题订阅表';
MySQL_QUERY
);

    }

    public function down(Schema $schema) : void
    {
        if ($schema->hasTable($this->tableName)) $schema->dropTable($this->tableName);
    }
}
