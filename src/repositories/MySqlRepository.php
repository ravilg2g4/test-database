<?php

declare(strict_types=1);

namespace App\repositories;

class MySqlRepository implements RepositoryInterface
{
    public function read(): void
    {

        $credMySql = new GetEnv();
        $credMySql->getCredMySql();
        $credMySql = $credMySql->credMySql;

        $mysql = new \mysqli(
            hostname: $credMySql['HOSTNAME'],
            username: $credMySql['USERNAME'],
            password: $credMySql['PASSWORD'],
            database: $credMySql['DATABASE'],
            port: $credMySql['PORT']
        );

        $sql = 'SELECT * FROM users;';
        $result = $mysql->query($sql);

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            print_r($row);
        }
    }

    public function write(): void
    {
        $credMySql = new GetEnv();
        $credMySql->getCredMySql();
        $credMySql = $credMySql->credMySql;

        $mysql = new \mysqli(
            hostname: $credMySql['HOSTNAME'],
            username: $credMySql['USERNAME'],
            password: $credMySql['PASSWORD'],
            database: $credMySql['DATABASE'],
            port: $credMySql['PORT']
        );

        $sql = 'SELECT * FROM users WHERE id = (SELECT MAX(id) FROM users);';
        $result = $mysql->query($sql);
        $result = $result->fetch_array(MYSQLI_ASSOC);
        $id = $result['id'] + 1;

        $name = readline('Имя нового пользователя: ');
        $surname = readline('Фамилия нового пользователя: ');
        $email = readline('Почта нового пользователя: ');

        $name = trim($name);
        $surname = trim($surname);
        $email = trim($email);

        $sql = 'INSERT users(id, name, surname, email) VALUES(?, ?, ?, ?)';
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param('isss',$id, $name, $surname, $email);
        $stmt->execute();
    }

    public function deleteId(): void
    {
        $credMySql = new GetEnv();
        $credMySql->getCredMySql();
        $credMySql = $credMySql->credMySql;

        $mysql = new \mysqli(
            hostname: $credMySql['HOSTNAME'],
            username: $credMySql['USERNAME'],
            password: $credMySql['PASSWORD'],
            database: $credMySql['DATABASE'],
            port: $credMySql['PORT']
        );

        $id = readline('Введите id пользователя, которого хотите удалить: ');
        $id = trim($id);

        $sql = 'DELETE FROM users WHERE id = ?';
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    public function deleteEmail(): void
    {
        $credMySql = new GetEnv();
        $credMySql->getCredMySql();
        $credMySql = $credMySql->credMySql;

        $mysql = new \mysqli(
            hostname: $credMySql['HOSTNAME'],
            username: $credMySql['USERNAME'],
            password: $credMySql['PASSWORD'],
            database: $credMySql['DATABASE'],
            port: $credMySql['PORT']
        );

        $email = readline('Введите почту пользователя: ');
        $email = trim($email);

        $sql = 'DELETE FROM users WHERE email = ?';
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
    }
}
