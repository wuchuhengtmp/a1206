<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210318034258 extends AbstractMigration
{
    private $_tableName = 'files';

    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
         $this->addSql(<<<MySQL_QUERY
            CREATE TABLE `{$this->_tableName}` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '路径',
              `disk` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '硬盘名',
              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文件表';
MySQL_QUERY
);

    }

    public function down(Schema $schema) : void
    {
        if ($schema->hasTable($this->_tableName)) $schema->dropTable($this->_tableName);
    }
}
