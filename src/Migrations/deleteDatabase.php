<?php

declare(strict_types=1);

namespace App\Migrations;

$mysql = new \mysqli(hostname: "8092a0bbc26b", username: "root", password: "root", port: 3306);

$sql = 'DROP DATABASE test;';
$mysql->query($sql);
