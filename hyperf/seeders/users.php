<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use Hyperf\DbConnection\Db;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::insert("INSERT INTO `users`(`id`, `username`, `password`, `role`, `created_at`, `updated_at`) VALUES (1, 'admin', '832c2c3ed4e89f21e9d06ab7f9388e7a51b467da37cec2651ba449ab64c2717f', 'admin', NULL, NULL)");
    }
}
