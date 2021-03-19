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
              `size` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文件表';
MySQL_QUERY
);

    }

    public function down(Schema $schema) : void
    {
        if ($schema->hasTable($this->_tableName)) $schema->dropTable($this->_tableName);
    }

    public function postUp(Schema $schema): void
    {
        $this->connection->exec("INSERT INTO `files`(`id`, `path`, `disk`, `created_at`, `updated_at`) VALUES (5, 'files/localDisk/2021-03-19/00001.mp3', 'qiniu', '2021-03-19 09:18:25', '2021-03-19 09:18:25')");
        $this->connection->exec("INSERT INTO `files`(`id`, `path`, `disk`, `created_at`, `updated_at`) VALUES (6, 'files/localDisk/2021-03-19/00002.mp3', 'qiniu', '2021-03-19 09:18:42', '2021-03-19 09:18:42') ");
        $this->connection->exec("INSERT INTO `files`(`id`, `path`, `disk`, `created_at`, `updated_at`) VALUES (7, 'files/localDisk/2021-03-19/00003.mp3', 'qiniu', '2021-03-19 09:18:45', '2021-03-19 09:18:45') ");
        $this->connection->exec("INSERT INTO `files`(`id`, `path`, `disk`, `created_at`, `updated_at`) VALUES (8, 'files/localDisk/2021-03-19/00004.mp3', 'qiniu', '2021-03-19 09:18:48', '2021-03-19 09:18:48') ");
        $this->connection->exec("INSERT INTO `files`(`id`, `path`, `disk`, `created_at`, `updated_at`) VALUES (9, 'files/localDisk/2021-03-19/00005.mp3', 'qiniu', '2021-03-19 09:18:51', '2021-03-19 09:18:51') ");
        $this->connection->exec("INSERT INTO `files`(`id`, `path`, `disk`, `created_at`, `updated_at`) VALUES (10, 'files/localDisk/2021-03-19/00006.mp3', 'qiniu', '2021-03-19 09:18:54', '2021-03-19 09:18:54')");
        $this->connection->exec("INSERT INTO `files`(`id`, `path`, `disk`, `created_at`, `updated_at`) VALUES (11, 'files/localDisk/2021-03-19/00007.mp3', 'qiniu', '2021-03-19 09:18:57', '2021-03-19 09:18:57')");
    }
}
