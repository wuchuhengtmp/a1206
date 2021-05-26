<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateConfigsTable extends Migration
{
    private $_tableName = 'configs';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $db_host = env('HYPERF_DB_HOST');
        $db_user = env('HYPERF_DB_USERNAME');
        $db_password = env('HYPERF_DB_PASSWORD');
        $db = env('HYPERF_DB_DATABASE');
        $port = env('HYPERF_DB_PORT');
        $dbh = new \PDO("mysql:host=$db_host;dbname=$db;port=$port", $db_user, $db_password);
        $dbh->exec("
              CREATE TABLE `{$this->_tableName}` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '配置名',
              `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '配置值',
              `created_at` timestamp NULL DEFAULT NULL,
              `deleted_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->_tableName);
    }
}
