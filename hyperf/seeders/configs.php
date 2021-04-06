<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use Hyperf\DbConnection\Db;

class Configs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::insert("INSERT INTO `configs`(`id`, `name`, `value`, `created_at`, `updated_at`) VALUES (1, 'DEFAULT_AUDIO_5', '5', '2021-03-19 09:30:35', '2021-03-19 15:08:43')");
        Db::insert("INSERT INTO `configs`(`id`, `name`, `value`, `created_at`, `updated_at`) VALUES (2, 'DEFAULT_AUDIO_6', '6', '2021-03-19 09:31:02', '2021-03-19 09:31:18')");
        Db::insert("INSERT INTO `configs`(`id`, `name`, `value`, `created_at`, `updated_at`) VALUES (3, 'DEFAULT_AUDIO_7', '7', '2021-03-19 09:31:03', '2021-03-19 09:31:11')");
        Db::insert("INSERT INTO `configs`(`id`, `name`, `value`, `created_at`, `updated_at`) VALUES (4, 'DEFAULT_AUDIO_8', '8', '2021-03-19 09:31:04', '2021-03-19 09:31:12')");
        Db::insert("INSERT INTO `configs`(`id`, `name`, `value`, `created_at`, `updated_at`) VALUES (5, 'DEFAULT_AUDIO_9', '9', '2021-03-19 09:31:05', '2021-03-19 09:31:13')");
        Db::insert("INSERT INTO `configs`(`id`, `name`, `value`, `created_at`, `updated_at`) VALUES (6, 'DEFAULT_AUDIO_10', '10', '2021-03-19 09:31:07', '2021-03-19 09:31:14')");
        Db::insert("INSERT INTO `configs`(`id`, `name`, `value`, `created_at`, `updated_at`) VALUES (7, 'DEFAULT_AUDIO_11', '11', '2021-03-19 09:31:08', '2021-03-19 09:31:15')");
        Db::insert("INSERT INTO `configs`(`id`, `name`, `value`, `created_at`, `updated_at`) VALUES (8, 'ABOUT', '<p>关于我们</p>', NULL, NULL)");
    }
}
