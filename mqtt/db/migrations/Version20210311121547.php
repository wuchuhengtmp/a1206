<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Utils\Encrypt;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210311121547 extends AbstractMigration
{
    private $tableName = 'users';

    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("
               CREATE TABLE `{$this->tableName}` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
                  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
                  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  `role` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'route' COMMENT '角色',
                  PRIMARY KEY (`id`) USING BTREE
                ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(Schema $schema) : void
    {
        if ($schema->hasTable($this->tableName)) {
            $schema->dropTable($this->tableName);
        }
    }

    public function postUp(Schema $schema): void
    {
        $this->connection->insert($this->tableName, array(
            'id' => 1,
            'username' => 'admin',
            'password' => Encrypt::hash('password'),
            'role' => 'admin'
        ));
        parent::postUp($schema);
    }
}
