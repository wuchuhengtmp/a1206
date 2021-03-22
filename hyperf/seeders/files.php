<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use Hyperf\DbConnection\Db;

class Files extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::insert("INSERT INTO `files`(`id`, `path`, `disk`, `created_at`, `updated_at`, `size`) VALUES (5, 'files/localDisk/2021-03-19/00001.mp3', 'qiniu', '2021-03-19 09:18:25', '2021-03-19 16:59:16', 10584)");
        Db::insert("INSERT INTO `files`(`id`, `path`, `disk`, `created_at`, `updated_at`, `size`) VALUES (6, 'files/localDisk/2021-03-19/00002.mp3', 'qiniu', '2021-03-19 09:18:42', '2021-03-19 23:59:01', 11928)");
        Db::insert("INSERT INTO `files`(`id`, `path`, `disk`, `created_at`, `updated_at`, `size`) VALUES (7, 'files/localDisk/2021-03-19/00003.mp3', 'qiniu', '2021-03-19 09:18:45', '2021-03-19 09:18:45', NULL)");
        Db::insert("INSERT INTO `files`(`id`, `path`, `disk`, `created_at`, `updated_at`, `size`) VALUES (8, 'files/localDisk/2021-03-19/00004.mp3', 'qiniu', '2021-03-19 09:18:48', '2021-03-19 09:18:48', NULL)");
        Db::insert("INSERT INTO `files`(`id`, `path`, `disk`, `created_at`, `updated_at`, `size`) VALUES (9, 'files/localDisk/2021-03-19/00005.mp3', 'qiniu', '2021-03-19 09:18:51', '2021-03-19 09:18:51', NULL)");
        Db::insert("INSERT INTO `files`(`id`, `path`, `disk`, `created_at`, `updated_at`, `size`) VALUES (10, 'files/localDisk/2021-03-19/00006.mp3', 'qiniu', '2021-03-19 09:18:54', '2021-03-19 09:18:54', NULL)");
        Db::insert("INSERT INTO `files`(`id`, `path`, `disk`, `created_at`, `updated_at`, `size`) VALUES (11, 'files/localDisk/2021-03-19/00007.mp3', 'qiniu', '2021-03-19 09:18:57', '2021-03-19 09:18:57', NULL)");
    }
}
