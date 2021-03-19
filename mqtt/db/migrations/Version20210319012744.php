<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210319012744 extends AbstractMigration
{
    private $_tableName = 'configs';

    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("
            CREATE TABLE `{$this->_tableName}` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '配置名',
              `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '配置值',
              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

    }

    public function down(Schema $schema) : void
    {
        if ($schema->hasTable($this->_tableName)) $schema->dropTable($this->_tableName);
    }

    public function postUp(Schema $schema): void
    {
        $this->connection->exec("INSERT INTO `configs`(`id`, `name`, `value`, `created_at`, `updated_at`) VALUES (1, 'DEFAULT_AUDIO_5', '7', '2021-03-19 09:30:35', '2021-03-19 09:30:52') ");
        $this->connection->exec("INSERT INTO `configs`(`id`, `name`, `value`, `created_at`, `updated_at`) VALUES (2, 'DEFAULT_AUDIO_6', 6, '2021-03-19 09:31:02', '2021-03-19 09:31:18')");
        $this->connection->exec("INSERT INTO `configs`(`id`, `name`, `value`, `created_at`, `updated_at`) VALUES (3, 'DEFAULT_AUDIO_7', 7, '2021-03-19 09:31:03', '2021-03-19 09:31:11')");
        $this->connection->exec("INSERT INTO `configs`(`id`, `name`, `value`, `created_at`, `updated_at`) VALUES (4, 'DEFAULT_AUDIO_8', 8, '2021-03-19 09:31:04', '2021-03-19 09:31:12')");
        $this->connection->exec("INSERT INTO `configs`(`id`, `name`, `value`, `created_at`, `updated_at`) VALUES (5, 'DEFAULT_AUDIO_9', 9, '2021-03-19 09:31:05', '2021-03-19 09:31:13')");
        $this->connection->exec("INSERT INTO `configs`(`id`, `name`, `value`, `created_at`, `updated_at`) VALUES (6, 'DEFAULT_AUDIO_10', 10, '2021-03-19 09:31:07', '2021-03-19 09:31:14')");
        $this->connection->exec("INSERT INTO `configs`(`id`, `name`, `value`, `created_at`, `updated_at`) VALUES (7, 'DEFAULT_AUDIO_11', 11, '2021-03-19 09:31:08', '2021-03-19 09:31:15')");
    }
}
