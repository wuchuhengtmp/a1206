<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateDevicefilesTable extends Migration
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
            CREATE TABLE `device_files` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `device_id` int(11) NOT NULL COMMENT '设备id',
              `file_id` int(11) NOT NULL COMMENT '文件id',
              PRIMARY KEY (`id`,`device_id`,`file_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='设备文件表';
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_files');
    }
}
