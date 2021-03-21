<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;

class Devices extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Hyperf\DbConnection\Db::insert("INSERT INTO `devices`(`id`, `device_id`, `user_id`, `ip_address`, `keepalive`, `protocol`, `status`, `vender`, `version`, `last_ack_at`, `created_at`, `connected_at`, `client_id`, `clean_session`, `play_state`, `play_mode`, `play_sound`, `alias`, `category_id`, `file_cnt`, `file_current`, `play_timer_sum`, `play_timer_cur`, `memory_size`, `trigger_modes`, `battery_vol`) VALUES (3, '868739052017831', 1, '192.168.0.43', 90, 'MQTT', 'online', 'XCWL', 'JRBJQ_AIR724_V01_01', '2021-03-18 10:56:12', '2021-03-18 10:56:12', '0000-00-00 00:00:00', '868739052017831', 1, '1', '0', 30, '868739052017831', 1, 33686018, 0, 16, 16, -1869574000, 0x5B7B226368616E656C223A302C226D6F6465223A307D2C7B226368616E656C223A312C226D6F6465223A307D2C7B226368616E656C223A322C226D6F6465223A307D2C7B226368616E656C223A332C226D6F6465223A307D5D, 0)");
    }
}
