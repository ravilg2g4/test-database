<?php

declare(strict_types=1);

namespace App\Migrations;

$mysql = new \mysqli(hostname: "8092a0bbc26b", username: "root",password: "root",port: 3306);

$sql = 'CREATE DATABASE test;';
$mysql->query($sql);

$mysql->select_db('test');

$sql = 'CREATE TABLE users (id INT, name VARCHAR(52), surname VARCHAR(52), email VARCHAR(52));';
$mysql->query($sql);

