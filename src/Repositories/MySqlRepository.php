<?php

declare(strict_types=1);

namespace App\Repositories;

class MySqlRepository implements RepositoryInterface
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

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            print_r($row);
        }
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
    public function write(): void
    {
        $mysql = $this->connectMySql();
        $newUser = $this->prepareNewUser();

        $sql = 'INSERT users(id, name, surname, email) VALUES(?, ?, ?, ?)';
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param('isss', $newUser['id'], $newUser['name'], $newUser['surname'], $newUser['email']);
        $stmt->execute();
    }
    private function checkWrite(): bool
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
    public function answerWrite(): void
    {
        $checkWrite = $this->checkWrite();
        if ($checkWrite === true) {
            $answer = ['answer' => 'Пользователь добавлен в базу данных'];
        } else {
            $answer = ['answer' => 'Пользователь не добавлен в базу данных'];
        }
        $answerJson = json_encode($answer,  JSON_UNESCAPED_UNICODE);
        print_r($answerJson);
    }
    public function deleteById(): void
    {
        $mysql = $this->connectMySql();
        $id = $_GET['id'];
        $sql = 'DELETE FROM users WHERE id = ?';
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
    private function checkDeleteById(): bool
    {
        $mysql = $this->connectMySql();
        $id = $_GET['id'];
        $sql = 'SELECT * FROM users WHERE id = ?';
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $checkId = $result->fetch_array(MYSQLI_ASSOC);
        $check = empty($checkId);
        return $check;
    }
    public function answerDeleteById(): void
    {
        $checkDelete = $this->checkDeleteById();
        if ($checkDelete === true) {
            $answer = ['answer' => 'Пользователь успешно удален из базы данных'];
        } else {
            $answer = ['answer' => 'Пользователь не был удален из базы данных'];
        }
        $answerJson = json_encode($answer,  JSON_UNESCAPED_UNICODE);
        print_r($answerJson);
    }
    public function deleteByEmail(): void
    {
        $mysql = $this->connectMySql();

        $email = readline('Введите почту пользователя: ');
        $email = trim($email);

        $sql = 'DELETE FROM users WHERE email = ?';
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
    }
}
