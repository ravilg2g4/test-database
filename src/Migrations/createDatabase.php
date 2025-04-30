<?php

declare(strict_types=1);

namespace App\Migrations;

require_once __DIR__.'/../../vendor/autoload.php';
use App\Repositories\GetCredMySql;

$credMySql = new GetCredMySql();
$credMySql->getCredMySql();
$credMySql = $credMySql->credMySql;

try {
    $dbh = new \PDO(
        "mysql:host={$credMySql['HOSTNAME']};",
        $credMySql['USERNAME'],
        $credMySql['PASSWORD']
    );
} catch (\PDOException $e) {
    die("Ошибка при подключении к MySQL: " . $e->getMessage());
}

$sql = 'CREATE DATABASE test;';
$dbh->query($sql);
$dbh->exec("use test;");
$sql = 'CREATE TABLE users (id INT, name VARCHAR(52), surname VARCHAR(52), email VARCHAR(52));';
$dbh->query($sql);
