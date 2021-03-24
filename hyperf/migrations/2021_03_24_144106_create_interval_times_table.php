<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateIntervalTimesTable extends Migration
{
    private $_tableName = 'interval_times';
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
        CREATE TABLE `{$this->_tableName}` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `devce_id` int(11) DEFAULT NULL COMMENT '设备表主键',
          `type_time` int(7) NOT NULL COMMENT '频率',
          `stime` int(11) DEFAULT NULL COMMENT '开始时间戳',
          `etime` int(11) DEFAULT NULL COMMENT '结束时间戳',
          `ctime` int(11) NOT NULL COMMENT '创建时间戳',
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='设备定时表';
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
