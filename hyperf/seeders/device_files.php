<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use Hyperf\DbConnection\Db;

class DeviceFiles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::insert("INSERT INTO `device_files`(`id`, `device_id`, `file_id`) VALUES (5, 3, 9)");
        Db::insert("INSERT INTO `device_files`(`id`, `device_id`, `file_id`) VALUES (6, 3, 10)");
        Db::insert("INSERT INTO `device_files`(`id`, `device_id`, `file_id`) VALUES (7, 3, 11)");
    }
}
