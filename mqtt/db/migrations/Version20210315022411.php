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
          `device_id` int(11) NOT NULL,
          `topic` varchar(255) NOT NULL,
          `qos` int(1) NOT NULL,
          `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='主题订阅表';
MySQL_QUERY
);

    }

    public function down(Schema $schema) : void
    {
        if ($schema->hasTable($this->tableName)) $schema->dropTable($this->tableName);
    }
}
