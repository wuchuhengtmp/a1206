<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $db_host = env('HYPERF_DB_HOST');
        $db_user = env('HYPERF_DB_USERNAME');
        $db_password = env('HYPERF_DB_PASSWORD');
        $db = env('HYPERF_DB_DATABASE');
        $dbh = new \PDO("mysql:host=$db_host;dbname=$db", $db_user, $db_password);
        $dbh->exec("
            CREATE TABLE `files` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '路径',
              `disk` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '硬盘名',
              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              `size` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文件表';
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
}
