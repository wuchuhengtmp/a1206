<?php

declare(strict_types=1);

namespace App\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;

/**
 * @Command
 */
class TryCreateDatabaseAndSeeder extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('tryCreateDatabaseAndSeeder:true');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('尝试创建数据库和填充数据');
    }

    public function handle()
    {
        $db_host = env('HYPERF_DB_HOST');
        $db_user = env('HYPERF_DB_USERNAME');
        $db_password = env('HYPERF_DB_PASSWORD');
        $db = env('HYPERF_DB_DATABASE');
        $db_port = env('HYPERF_DB_PORT');
        try {
            $dbh = new \PDO("mysql:host=$db_host;port=$db_port", $db_user, $db_password);
        } catch (PDOException $e) {
            $this->line($e->getMessage(), 'error');
            die();
        }

        $stmt = $dbh->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '${db}'");
        $is_db_exists = (bool) $stmt->fetchColumn();
        if (!$is_db_exists) {
            try{
                $dbh->exec("CREATE DATABASE `$db`;");
                $this->line("create database $db success", 'info');
                // migrate database
                $this->call('migrate:fresh');
                // inport seeder
                $this->call('db:seed', array_filter([
                    '--database' => 'default',
                    '--force' => true,
                ]));
            } catch (\Exception $E) {
                $this->line($E->getMessage(), 'error');
            }
        }
    }
}
