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

        try {
            $dbh = new \PDO(
                "mysql:host={$credMySql['HOSTNAME']};dbname={$credMySql['DATABASE']}",
                $credMySql['USERNAME'],
                $credMySql['PASSWORD']);
        }
        catch (\PDOException $e) {
            die("Ошибка при подключении к MySQL: " . $e->getMessage());
        }

        return $dbh;
    }
    public function read(): void
    {
        $dbh = $this->connectMySql();
        $sql = 'SELECT * FROM users;';
        $result = $dbh->query($sql);

        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            print_r($row);
        }
    }
    public function create(): void
    {
        $dbh = $this->connectMySql();
        $id = $this->getIdNewUser();
        $this->getNewUser();
        $newUser = $this->newUser;

        $sql = 'INSERT users(id, name, surname, email) VALUES(:id, :name, :surname, :email)';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->bindValue(':name', $newUser['name'], \PDO::PARAM_STR);
        $stmt->bindValue(':surname', $newUser['surname'], \PDO::PARAM_STR);
        $stmt->bindValue(':email', $newUser['email'], \PDO::PARAM_STR);
        $stmt->execute();
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
        $dbh = $this->connectMySql();
        $sql = 'SELECT * FROM users WHERE id = (SELECT MAX(id) FROM users);';
        $result = $dbh->query($sql);
        $lastUser = $result->fetch(\PDO::FETCH_ASSOC);
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
        $dbh = $this->connectMySql();

        $id = readline('Введите id пользователя, которого хотите удалить: ');
        $id = trim($id);
        $this->valueDelete = $id;

        $sql = 'DELETE FROM users WHERE id = :id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }
    private function deleteEmail(): void
    {
        $dbh = $this->connectMySql();

        $email = readline('Введите почту пользователя: ');
        $email = trim($email);
        $this->valueDelete = $email;

        $sql = 'DELETE FROM users WHERE email = :email';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();
    }
    private function checkDelete(): bool
    {
        $dbh = $this->connectMySql();
        $valueDelete = $this->valueDelete;
        $sql = $this->createSqlRequestCheck();

        $stmt = $dbh->prepare($sql);
        $typeValueDelete = $this->getTypeValueDelete();
        $stmt->bindValue(':valueDelete', $valueDelete, $typeValueDelete);
        $stmt->execute();
        $checkId = $stmt->fetch(\PDO::FETCH_ASSOC);
        $check = empty($checkId);
        return $check;
    }
    private function createSqlRequestCheck(): string
    {
        $choiceDelete = $this->choiceDelete;
        if ($choiceDelete === 'id') {
            $sql = 'SELECT * FROM users WHERE id = :valueDelete';
        } elseif ($choiceDelete === 'email') {
            $sql = 'SELECT * FROM users WHERE email = :valueDelete';
        }
        return $sql;
    }
    private function getTypeValueDelete(): int
    {
        $choiceDelete = $this->choiceDelete;
        if ($choiceDelete === 'id') {
            $typeValueDelete = \PDO::PARAM_INT;
        } elseif ($choiceDelete === 'email') {
            $typeValueDelete = \PDO::PARAM_STR;
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