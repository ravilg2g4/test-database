<?php

declare(strict_types=1);

namespace App\Migrations;

require_once __DIR__.'/../../vendor/autoload.php';

use App\Repositories\MySql\CredMySql;

$credMySql = new CredMySql();
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

$sql = 'CREATE DATABASE project;';
$dbh->query($sql);
$dbh->exec("use project;");
$sql = 'CREATE TABLE users (id INT, name VARCHAR(52), surname VARCHAR(52), email VARCHAR(52));';
$dbh->query($sql);

$id = 1;
$name = 'name';
$surname = 'surname';
$email = 'email@gmail.com';

$sql = 'INSERT users(id, name, surname, email) VALUES(:id, :name, :surname, :email)';
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':id', $id, \PDO::PARAM_INT);
$stmt->bindValue(':name', $name, \PDO::PARAM_STR);
$stmt->bindValue(':surname', $surname, \PDO::PARAM_STR);
$stmt->bindValue(':email', $email, \PDO::PARAM_STR);
$stmt->execute();

$id = 2;
$name = 'name2';
$surname = 'surname2';
$email = 'email@gmail.com2';

$stmt = $dbh->prepare($sql);
$stmt->bindValue(':id', $id, \PDO::PARAM_INT);
$stmt->bindValue(':name', $name, \PDO::PARAM_STR);
$stmt->bindValue(':surname', $surname, \PDO::PARAM_STR);
$stmt->bindValue(':email', $email, \PDO::PARAM_STR);
$stmt->execute();
