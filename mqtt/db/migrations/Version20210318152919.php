<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210318152919 extends AbstractMigration
{
    private $_tableName = 'device_files';

    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql(<<<MYSQL_QUERY
            CREATE TABLE `{$this->_tableName}` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `device_id` int(11) NOT NULL COMMENT '设备id',
              `file_id` int(11) NOT NULL COMMENT '文件id',
              PRIMARY KEY (`id`,`device_id`,`file_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='设备文件表';
MYSQL_QUERY
);
    }

    public function down(Schema $schema) : void
    {
        if ($schema->hasTable($this->_tableName)) $schema->dropTable($this->_tableName);
    }

    public function postUp(Schema $schema): void
    {
        $this->connection->exec("INSERT INTO `{$this->_tableName}`(`id`, `device_id`, `file_id`) VALUES (1, 3, 7) ");
        $this->connection->exec("INSERT INTO `{$this->_tableName}`(`id`, `device_id`, `file_id`) VALUES (2, 3, 6) ");
        $this->connection->exec("INSERT INTO `{$this->_tableName}`(`id`, `device_id`, `file_id`) VALUES (3, 3, 7) ");
        $this->connection->exec("INSERT INTO `{$this->_tableName}`(`id`, `device_id`, `file_id`) VALUES (4, 3, 8) ");
        $this->connection->exec("INSERT INTO `{$this->_tableName}`(`id`, `device_id`, `file_id`) VALUES (5, 3, 9) ");
        $this->connection->exec("INSERT INTO `{$this->_tableName}`(`id`, `device_id`, `file_id`) VALUES (6, 3, 10)");
        $this->connection->exec("INSERT INTO `{$this->_tableName}`(`id`, `device_id`, `file_id`) VALUES (7, 3, 11)");
    }
}
