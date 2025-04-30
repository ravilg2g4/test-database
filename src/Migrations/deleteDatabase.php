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
        "mysql:host={$credMySql['HOSTNAME']};dbname={$credMySql['DATABASE']}",
        $credMySql['USERNAME'],
        $credMySql['PASSWORD']
    );
} catch (\PDOException $e) {
    die("Ошибка при подключении к MySQL: " . $e->getMessage());
}


$sql = 'DROP DATABASE test;';
$dbh->query($sql);
