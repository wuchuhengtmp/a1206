<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Utils\Encrypt;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210317071433 extends AbstractMigration
{
    private $_tableName = 'categories';

    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql(<<<MySQL_QUERY
            CREATE TABLE `{$this->_tableName}` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='分类表';
MySQL_QUERY
);

    }

    public function down(Schema $schema) : void
    {
        if ($schema->hasTable($this->_tableName)) $schema->dropTable($this->_tableName);
    }


    public function postUp(Schema $schema): void
    {
        $this->connection->insert($this->_tableName, array( 'id' => 1, 'name' => '分类1', ));
        $this->connection->insert($this->_tableName, array( 'id' => 2, 'name' => '分类2', ));
        $this->connection->insert($this->_tableName, array( 'id' => 3, 'name' => '分类3', ));
        $this->connection->insert($this->_tableName, array( 'id' => 4, 'name' => '分类4', ));
        $this->connection->insert($this->_tableName, array( 'id' => 5, 'name' => '分类5', ));
        parent::postUp($schema);
    }

}
