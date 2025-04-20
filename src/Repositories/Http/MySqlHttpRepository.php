<?php

declare(strict_types=1);

namespace App\Repositories\Http;

use App\Repositories\RepositoryInterface;
use App\Repositories\GetCredMySql;

class MySqlHttpRepository implements RepositoryInterface
{
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
        $result = $result->fetch_all(MYSQLI_ASSOC);
        $answer = json_encode($result, JSON_UNESCAPED_UNICODE);
        print_r($answer);
    }
    private function getJsonFromPost(): array
    {
        $json = file_get_contents('php://input');
        $newUser = json_decode($json, true);
        return $newUser;
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
    private function prepareNewUser(): array
    {
        $newUser = $this->getJsonFromPost();
        $newUser['name'] = trim($newUser['name']);
        $newUser['surname'] = trim($newUser['surname']);
        $newUser['email'] = trim($newUser['email']);

        $idNewUser = $this->getIdNewUser();
        $newUser = array_merge(['id' => $idNewUser], $newUser);

        return $newUser;
    }
    public function create(): void
    {
        $mysql = $this->connectMySql();
        $newUser = $this->prepareNewUser();

        $sql = 'INSERT users(id, name, surname, email) VALUES(?, ?, ?, ?)';
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param('isss', $newUser['id'], $newUser['name'], $newUser['surname'], $newUser['email']);
        $stmt->execute();
    }
    private function checkCreate(): bool
    {
        $mysql = $this->connectMySql();
        $sql = 'SELECT * FROM users WHERE id = (SELECT MAX(id) FROM users);';
        $result = $mysql->query($sql);
        $lastUser = $result->fetch_array(MYSQLI_ASSOC);
        $newUser = $this->getJsonFromPost();
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
            $answer = ['answer' => 'Пользователь добавлен в базу данных'];
        } else {
            $answer = ['answer' => 'Пользователь не добавлен в базу данных'];
        }
        $answerJson = json_encode($answer, JSON_UNESCAPED_UNICODE);
        print_r($answerJson);
    }
    public function choiceDelete(): string
    {
        if (array_key_exists('id', $_GET)) {
            $choiceDelete = 'id';
        } elseif (array_key_exists('email', $_GET)) {
            $choiceDelete = 'email';
        } else {
            $answer = ['answerError' => 'Не указан параметр для удаления пользователя'];
            print_r($answer);
            exit();
        }
        return $choiceDelete;
    }
    private function getTypeValueDelete(): string
    {
        $choiceDelete = $this->choiceDelete();
        if ($choiceDelete === 'id') {
            $typeValueDelete = 'i';
        } elseif ($choiceDelete === 'email') {
            $typeValueDelete = 's';
        }
        return $typeValueDelete;
    }
    private function getValueDelete(): int|string
    {
        $choiceDelete = $this->choiceDelete();
        if ($choiceDelete === 'id') {
            $valueDelete = (int)$_GET['id'];
        } elseif ($choiceDelete === 'email') {
            $valueDelete = $_GET['email'];
        }
        return $valueDelete;
    }
    private function createSqlRequestDelete(): string
    {
        $choiceDelete = $this->choiceDelete();
        if ($choiceDelete === 'id') {
            $sql = 'DELETE FROM users WHERE id = ?';
        } elseif ($choiceDelete === 'email') {
            $sql = 'DELETE FROM users WHERE email = ?';
        }
        return $sql;
    }
    private function createSqlRequestCheck(): string
    {
        $choiceDelete = $this->choiceDelete();
        if ($choiceDelete === 'id') {
            $sql = 'SELECT * FROM users WHERE id = ?';
        } elseif ($choiceDelete === 'email') {
            $sql = 'SELECT * FROM users WHERE email = ?';
        }
        return $sql;
    }
    public function delete(): void
    {
        $mysql = $this->connectMySql();
        $valueDelete = $this->getValueDelete();
        $sql = $this->createSqlRequestDelete();

        $stmt = $mysql->prepare($sql);
        $typeValueDelete = $this->getTypeValueDelete();
        $stmt->bind_param($typeValueDelete, $valueDelete);
        $stmt->execute();
    }
    private function checkDelete(): bool
    {
        $mysql = $this->connectMySql();
        $valueDelete = $this->getValueDelete();
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
    public function answerDelete(): void
    {
        $checkDelete = $this->checkDelete();
        if ($checkDelete === true) {
            $answer = ['answer' => 'Пользователь успешно удален из базы данных'];
        } else {
            $answer = ['answer' => 'Пользователь не был удален из базы данных'];
        }
        $answerJson = json_encode($answer, JSON_UNESCAPED_UNICODE);
        print_r($answerJson);
    }
}
