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

$id = 1;
$name = 'user';
$surname = 'user';
$email = 'user@gmail.com';

$sql = 'INSERT users(id, name, surname, email) VALUES(:id, :name, :surname, :email)';
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':id', $id, \PDO::PARAM_INT);
$stmt->bindValue(':name', $name, \PDO::PARAM_STR);
$stmt->bindValue(':surname', $surname, \PDO::PARAM_STR);
$stmt->bindValue(':email', $email, \PDO::PARAM_STR);
$stmt->execute();
