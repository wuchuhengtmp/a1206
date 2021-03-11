#!/usr/bin/env php
<?php

! defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));

require_once BASE_PATH . '/vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Tools\Console\Command;
use Symfony\Component\Console\Application;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(BASE_PATH .'/.env');

$db = env('DB_NAME');
$dbParams = [
    'dbname' => env('DB_NAME'),
    'user' => env('DB_USER'),
    'password' => env('DB_PASSWORD'),
    'host' => env('DB_HOST'),
    'driver' => 'pdo_mysql',
];

try {
    $dbh = new \PDO("mysql:host={$dbParams['host']}", $dbParams['user'], $dbParams['password']);
} catch (PDOException $e) {
    echo "It was failed to connect database";
    die();
}

$stmt = $dbh->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '${db}'");
$is_db_exists = (bool) $stmt->fetchColumn();
if (!$is_db_exists) {
    try{
        $dbh->exec("CREATE DATABASE `$db`;");
    } catch (\Exception $E) {
        echo "It was failed to create database\n";
        die();
    }
}


$connection = DriverManager::getConnection($dbParams);


$config = new PhpFile(BASE_PATH . '/config/migrations.php'); // Or use one of the Doctrine\Migrations\Configuration\Configuration\* loaders

$dependencyFactory = DependencyFactory::fromConnection($config, new ExistingConnection($connection));

$cli = new Application('Doctrine Migrations');
$cli->setCatchExceptions(true);

$cli->addCommands(array(
    new Command\DumpSchemaCommand($dependencyFactory),
    new Command\ExecuteCommand($dependencyFactory),
    new Command\GenerateCommand($dependencyFactory),
    new Command\LatestCommand($dependencyFactory),
    new Command\ListCommand($dependencyFactory),
    new Command\MigrateCommand($dependencyFactory),
    new Command\RollupCommand($dependencyFactory),
    new Command\StatusCommand($dependencyFactory),
    new Command\SyncMetadataCommand($dependencyFactory),
    new Command\VersionCommand($dependencyFactory),
));

$cli->run();