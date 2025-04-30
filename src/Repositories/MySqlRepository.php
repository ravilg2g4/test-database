<?php

namespace App\Repositories;

use App\Exceptions\CreateNewUserException;
use App\Exceptions\DeleteUserException;
use App\Exceptions\EntityNotFoundException;

class MySqlRepository implements RepositoryInterface
{
    public function connectMySql(): object
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
        $answer = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        print_r($answer);
    }
    public function create(NewUser $newUser): void
    {
        $dbh = $this->connectMySql();
        $sql = 'INSERT users(id, name, surname, email) VALUES(:id, :name, :surname, :email)';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', $newUser->id, \PDO::PARAM_INT);
        $stmt->bindValue(':name', $newUser->name, \PDO::PARAM_STR);
        $stmt->bindValue(':surname', $newUser->surname, \PDO::PARAM_STR);
        $stmt->bindValue(':email', $newUser->email, \PDO::PARAM_STR);
        $create = $stmt->execute();
        if ($create === false) {
            throw new CreateNewUserException();
        }
    }
    public function delete(string $choiceDelete, string|int $valueDelete): void
    {
        $dbh = $this->connectMySql();
        if ($choiceDelete === 'id') {
            $sql = 'DELETE FROM users WHERE id = :valueDelete;';
            $typeValueDelete = \PDO::PARAM_INT;
        } elseif ($choiceDelete === 'email') {
            $sql = 'DELETE FROM users WHERE email = :valueDelete;';
            $typeValueDelete = \PDO::PARAM_STR;
        }
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':valueDelete', $valueDelete, $typeValueDelete);
        $delete = $stmt->execute();
        if ($delete === false) {
            throw new DeleteUserException(message: 'Ошибка при выполнении DELETE запроса к MySQL');
        }
        if ($stmt->rowCount() === 0) {
            throw new EntityNotFoundException(entityName: 'user', failed: $choiceDelete, fieldValue: $valueDelete);
        }
    }
}
