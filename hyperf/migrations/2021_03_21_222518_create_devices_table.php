<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
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
            CREATE TABLE `devices` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `device_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '设备ID',
              `user_id` int(11) NOT NULL COMMENT '用户ID',
              `ip_address` varchar(39) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '设备ip',
              `keepalive` int(11) NOT NULL COMMENT '心跳',
              `protocol` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '连接协议',
              `status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'online 或 offline',
              `vender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '厂商代码',
              `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
              `last_ack_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后通信',
              `created_at` timestamp not null default current_timestamp comment '创建时间',
              `connected_at` timestamp not null comment '连接时间',
              `client_id` varchar(255) character set utf8mb4 collate utf8mb4_unicode_ci not null comment 'mqtt设备id',
              `clean_session` int(1) not null comment '1是0否',
              `play_state` varchar(2) character set utf8mb4 collate utf8mb4_unicode_ci default null comment '播放状态定义：系统上电处于停止状态00(停止)01(播放)02(暂停)',
              `play_mode` varchar(2) character set utf8mb4 collate utf8mb4_unicode_ci default null comment '(00)按顺序播放全盘曲目,播放完后循环播放单曲循环 (01)一直循环播放当前曲目单曲停止 (02)播放完当前曲目一次停止全盘随机(03)随机播放盘符内曲目目录循环 (04)按顺序播放当前文件夹内曲目,播放完后循环播放 (05)在当前目录内随机播放，目录不包含子目录目录顺序播放 (06)按顺序播放当前文件夹内曲目,播放完后停止 (07)按顺序播放全盘曲目,播放完后停止\n',
              `play_sound` int(2) default null comment '0~30 对应31级音量',
              `alias` varchar(255) character set utf8mb4 collate utf8mb4_unicode_ci default '' comment '设备别名',
              `category_id` int(10) not null default '1' comment '分类id',
              `file_cnt` int(255) default null comment '总曲目数',
              `file_current` int(255) default null comment '当前曲目',
              `play_timer_sum` int(11) default null comment '总曲目播放时间',
              `play_timer_cur` int(11) default null comment '当前曲目播放时间',
              `memory_size` int(11) default null comment '内存余量',
              `trigger_modes` varbinary(255) default null,
              `battery_vol` int(255) default null comment '电力',
              `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              primary key (`id`)
            ) engine=innodb auto_increment=4 default charset=utf8mb4 collate=utf8mb4_unicode_ci;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
}
