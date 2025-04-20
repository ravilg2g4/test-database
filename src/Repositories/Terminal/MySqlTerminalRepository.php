<?php

declare(strict_types=1);

namespace App\Repositories\Terminal;

use App\Repositories\RepositoryInterface;
use App\Repositories\GetCredMySql;
use Couchbase\User;

class MySqlTerminalRepository implements RepositoryInterface
{
    private string $choiceDelete;
    private string $valueDelete;
    private array $newUser;
    private function connectMySql(): object
    {
        $credMySql = new GetCredMySql();
        $credMySql->getCredMySql();
        $credMySql = $credMySql->credMySql;

        mysqli_report(MYSQLI_REPORT_OFF);

        $mysql = @new \mysqli(
            hostname: $credMySql['HOSTNAME'],
            username: $credMySql['USERNAME'],
            password: $credMySql['PASSWORD'],
            database: $credMySql['DATABASE'],
            port: $credMySql['PORT']
        );

        if ($mysql->connect_error) {
            error_log("Ошибка при подключении к MySQL: " . $mysql->connect_error);
            exit();
        }

        return $mysql;
    }
    public function read(): void
    {

        $mysql = $this->connectMySql();

        $sql = 'SELECT * FROM users;';
        $result = $mysql->query($sql);

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            print_r($row);


        }
    }
    public function create(): void
    {
        $mysql = $this->connectMySql();
        $id = $this->getIdNewUser();
        $this->getNewUser();
        $newUser = $this->newUser;

        $sql = 'INSERT users(id, name, surname, email) VALUES(?, ?, ?, ?)';
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param('isss',$id, $newUser['name'], $newUser['surname'], $newUser['email']);
        $stmt->execute();
    }
    private function getIdNewUser(): int
    {
        $mysql = $this->connectMySql();
        $sql = 'SELECT * FROM users WHERE id = (SELECT MAX(id) FROM users);';
        $result = $mysql->query($sql);
        $result = $result->fetch_array(MYSQLI_ASSOC);
        $id = $result['id'] + 1;
        return $id;
    }
    private function getNewUser(): void
    {
        $name = readline('Имя нового пользователя: ');
        $surname = readline('Фамилия нового пользователя: ');
        $email = readline('Почта нового пользователя: ');

        $name = trim($name);
        $surname = trim($surname);
        $email = trim($email);

        $newUser = ['name' => $name, 'surname' => $surname, 'email' => $email];
        $this->newUser = $newUser;
    }
    private function checkCreate(): bool
    {
        $mysql = $this->connectMySql();
        $sql = 'SELECT * FROM users WHERE id = (SELECT MAX(id) FROM users);';
        $result = $mysql->query($sql);
        $lastUser = $result->fetch_array(MYSQLI_ASSOC);
        $newUser = $this->newUser;
        array_shift($lastUser);

        if ($newUser === $lastUser) {
            return true;
        } else {
            return false;
        }
    }
    public function answerCreate(): void
    {
        $checkWrite = $this->checkCreate();
        if ($checkWrite === true) {
            $answer = 'Пользователь добавлен в базу данных' . PHP_EOL;
        } else {
            $answer = 'Пользователь не добавлен в базу данных' . PHP_EOL;
        }
        echo $answer;
    }
    public function delete(): void
    {
        $this->choiceDelete();
        $choiceDelete = $this->choiceDelete;
        if ($choiceDelete === 'id') {
            $this->deleteId();
        } elseif ($choiceDelete === 'email') {
            $this->deleteEmail();
        }
    }
    private function choiceDelete(): void
    {
        $choiceDelete = readline('Вы знаете id или по почту? (id/email): ');
        $choiceDelete = trim($choiceDelete);
        $this->choiceDelete = $choiceDelete;
    }
    private function deleteId(): void
    {
        $mysql = $this->connectMySql();

        $id = readline('Введите id пользователя, которого хотите удалить: ');
        $id = trim($id);
        $this->valueDelete = $id;

        $sql = 'DELETE FROM users WHERE id = ?';
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
    private function deleteEmail(): void
    {
        $mysql = $this->connectMySql();

        $email = readline('Введите почту пользователя: ');
        $email = trim($email);
        $this->valueDelete = $email;

        $sql = 'DELETE FROM users WHERE email = ?';
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
    }
    private function checkDelete(): bool
    {
        $mysql = $this->connectMySql();
        $valueDelete = $this->valueDelete;
        $sql = $this->createSqlRequestCheck();

        $stmt = $mysql->prepare($sql);
        $typeValueDelete = $this->getTypeValueDelete();
        $stmt->bind_param($typeValueDelete, $valueDelete);
        $stmt->execute();

        $result = $stmt->get_result();
        $checkId = $result->fetch_array(MYSQLI_ASSOC);
        $check = empty($checkId);
        return $check;
    }
    private function createSqlRequestCheck(): string
    {
        $choiceDelete = $this->choiceDelete;
        if ($choiceDelete === 'id') {
            $sql = 'SELECT * FROM users WHERE id = ?';
        } elseif ($choiceDelete === 'email') {
            $sql = 'SELECT * FROM users WHERE email = ?';
        }
        return $sql;
    }
    private function getTypeValueDelete(): string
    {
        $choiceDelete = $this->choiceDelete;
        if ($choiceDelete === 'id') {
            $typeValueDelete = 'i';
        } elseif ($choiceDelete === 'email') {
            $typeValueDelete = 's';
        }
        return $typeValueDelete;
    }
    public function answerDelete(): void
    {
        $checkDelete = $this->checkDelete();
        if ($checkDelete === true) {
            $answer = 'Пользователь успешно удален из базы данных' . PHP_EOL;
        } else {
            $answer = 'Пользователь не был удален из базы данных' . PHP_EOL;
        }
        print_r($answer);
    }
}