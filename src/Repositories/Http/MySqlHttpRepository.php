<?php

declare(strict_types=1);

namespace App\Repositories\Http;

use App\Repositories\RepositoryInterface;
use App\Repositories\GetCredMySql;
use mysqli;

class MySqlHttpRepository implements RepositoryInterface
{
    private function connectMySql(): object
    {
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

        return $dbh;
    }
    public function read(): void
    {
        $dbh = $this->connectMySql();

        $sql = 'SELECT * FROM users;';
        $result = $dbh->query($sql);
        $result = $result->fetchAll(\PDO::FETCH_ASSOC);
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
        $dbh = $this->connectMySql();
        $sql = 'SELECT * FROM users WHERE id = (SELECT MAX(id) FROM users);';
        $result = $dbh->query($sql);
        $result = $result->fetch(\PDO::FETCH_ASSOC);
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
        $dbh = $this->connectMySql();
        $newUser = $this->prepareNewUser();

        $sql = 'INSERT users(id, name, surname, email) VALUES(:id, :name, :surname, :email)';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', $newUser['id'], \PDO::PARAM_INT);
        $stmt->bindValue(':name', $newUser['name'], \PDO::PARAM_STR);
        $stmt->bindValue(':surname', $newUser['surname'], \PDO::PARAM_STR);
        $stmt->bindValue(':email', $newUser['email'], \PDO::PARAM_STR);
        $stmt->execute();
    }
    private function checkCreate(): bool
    {
        $dbh = $this->connectMySql();
        $sql = 'SELECT * FROM users WHERE id = (SELECT MAX(id) FROM users);';
        $result = $dbh->query($sql);
        $lastUser = $result->fetch(\PDO::FETCH_ASSOC);
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
        $request = $_SERVER['REQUEST_URI'];
        $requestArray = explode('/', $request);
        $choiceDelete = $requestArray[count($requestArray) - 2];
        if ($choiceDelete !== 'id' && $choiceDelete !== 'email') {
            $answer = ['answerError' => 'Не указан параметр для удаления пользователя'];
            print_r($answer);
            exit();
        }
        return $choiceDelete;
    }
    private function getTypeValueDelete(): int
    {
        $choiceDelete = $this->choiceDelete();
        if ($choiceDelete === 'id') {
            $typeValueDelete = \PDO::PARAM_INT;
        } elseif ($choiceDelete === 'email') {
            $typeValueDelete = \PDO::PARAM_STR;
        }
        return $typeValueDelete;
    }
    private function getValueDelete(): int|string
    {
        $request = $_SERVER['REQUEST_URI'];
        $requestArray = explode('/', $request);
        $valueDelete = $requestArray[count($requestArray) - 1];

        $choiceDelete = $this->choiceDelete();
        if ($choiceDelete === 'id') {
            $valueDelete = (int)$valueDelete;
        }
        return $valueDelete;
    }
    private function createSqlRequestDelete(): string
    {
        $choiceDelete = $this->choiceDelete();
        if ($choiceDelete === 'id') {
            $sql = 'DELETE FROM users WHERE id = :valueDelete;';
        } elseif ($choiceDelete === 'email') {
            $sql = 'DELETE FROM users WHERE email = :valueDelete;';
        }
        return $sql;
    }
    private function createSqlRequestCheck(): string
    {
        $choiceDelete = $this->choiceDelete();
        if ($choiceDelete === 'id') {
            $sql = 'SELECT * FROM users WHERE id = :valueDelete;';
        } elseif ($choiceDelete === 'email') {
            $sql = 'SELECT * FROM users WHERE email = :valueDelete;';
        }
        return $sql;
    }
    public function delete(): void
    {
        $dbh = $this->connectMySql();
        $valueDelete = $this->getValueDelete();
        $sql = $this->createSqlRequestDelete();

        $stmt = $dbh->prepare($sql);
        $typeValueDelete = $this->getTypeValueDelete();
        $stmt->bindValue(':valueDelete', $valueDelete, $typeValueDelete);
        $stmt->execute();
    }
    private function checkDelete(): bool
    {
        $dbh = $this->connectMySql();
        $valueDelete = $this->getValueDelete();
        $sql = $this->createSqlRequestCheck();

        $stmt = $dbh->prepare($sql);
        $typeValueDelete = $this->getTypeValueDelete();
        $stmt->bindValue(':valueDelete', $valueDelete, $typeValueDelete);
        $stmt->execute();
        $checkId = $stmt->fetch(\PDO::FETCH_ASSOC);
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
