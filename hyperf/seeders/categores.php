<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use Hyperf\DbConnection\Db;

class Categores extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert("INSERT INTO `categories`(`id`, `name`, `created_at`, `updated_at`) VALUES (1, '分类1', '2021-03-19 10:32:47', '2021-03-19 10:32:47')");
        DB::insert("INSERT INTO `categories`(`id`, `name`, `created_at`, `updated_at`) VALUES (2, '分类2', '2021-03-19 10:32:47', '2021-03-19 10:32:47')");
        DB::insert("INSERT INTO `categories`(`id`, `name`, `created_at`, `updated_at`) VALUES (3, '分类3', '2021-03-19 10:32:47', '2021-03-19 10:32:47')");
        DB::insert("INSERT INTO `categories`(`id`, `name`, `created_at`, `updated_at`) VALUES (4, '分类4', '2021-03-19 10:32:47', '2021-03-19 10:32:47')");
        DB::insert("INSERT INTO `categories`(`id`, `name`, `created_at`, `updated_at`) VALUES (5, '分类5', '2021-03-19 10:32:47', '2021-03-19 10:32:47')");
    }
}
