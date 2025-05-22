<?php

declare(strict_types=1);

namespace App\Tests\Repositories\MySql;

use App\DTO\DeleteUserDTO;
use App\DTO\NewUserDTO;
use App\Exceptions\EntityNotFoundException;
use App\Repositories\MySql\CredMySql;
use App\Repositories\MySql\MySqlRepository;
use PHPUnit\Framework\TestCase;

class MySqlRepositoryTest extends TestCase
{
    private MySqlRepository $repository;
    private object $dbh;
    private array $credMySql;

    protected function setUp(): void
    {
        $credMySql = new CredMySql();
        $credMySql = $credMySql->credMySql;
        $this->credMySql = $credMySql;
        $this->createTestDatabase();
        $this->dbh = new \PDO(
            "mysql:host={$credMySql['HOSTNAME']};dbname=test;",
            $credMySql['USERNAME'],
            $credMySql['PASSWORD']
        );
        $this->repository = new MySqlRepository($this->dbh);
    }
    protected function tearDown(): void
    {
        $this->deleteTestDatabase();
    }
    public function testRead(): void
    {
        $expectedData = [[
            "id" => 1,
            "name" => "name",
            "surname" => "surname",
            "email" => "email@gmail.com"
        ], [
            "id" => 2,
            "name" => "name2",
            "surname" => "surname2",
            "email" => "email@gmail.com2"
        ]];
        $expectedData = json_encode($expectedData, JSON_PRETTY_PRINT);

        $this->repository
            ->read();

        $this->expectOutputString($expectedData);
    }
    public function testCreate(): void
    {
        $expectedData = [[
            "id" => 1,
            "name" => "name",
            "surname" => "surname",
            "email" => "email@gmail.com"
        ], [
            "id" => 2,
            "name" => "name2",
            "surname" => "surname2",
            "email" => "email@gmail.com2"
        ], [
            "id" => 3,
            "name" => "newName",
            "surname" => "newSurname",
            "email" => "new.email@gmail.com"
        ]];
        $expectedData = json_encode($expectedData, JSON_PRETTY_PRINT);
        $newUser = new NewUserDTO([
            "name" => "newName",
            "surname" => "newSurname",
            "email" => "new.email@gmail.com"
        ]);

        $this->repository
            ->create($newUser);

        $dbh = $this->dbh;
        $sql = 'SELECT * FROM users;';
        $result = $dbh->query($sql);
        $result = $result->fetchAll(\PDO::FETCH_ASSOC);
        $resultData = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $this->assertEquals($expectedData, $resultData);
    }
    public function testDeleteById(): void
    {
        $expectedData = [[
            "id" => 1,
            "name" => "name",
            "surname" => "surname",
            "email" => "email@gmail.com"
        ]];
        $expectedData = json_encode($expectedData, JSON_PRETTY_PRINT);
        $deleteUser = new DeleteUserDTO([
            'paramDelete' => 'id',
            'valueDelete' => 2,
        ]);

        $this->repository
            ->delete($deleteUser);

        $dbh = $this->dbh;
        $sql = 'SELECT * FROM users;';
        $result = $dbh->query($sql);
        $result = $result->fetchAll(\PDO::FETCH_ASSOC);
        $resultData = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $this->assertEquals($expectedData, $resultData);
    }
    public function testDeleteByEmail(): void
    {
        $expectedData = [[
            "id" => 1,
            "name" => "name",
            "surname" => "surname",
            "email" => "email@gmail.com"
        ]];
        $expectedData = json_encode($expectedData, JSON_PRETTY_PRINT);
        $deleteUser = new DeleteUserDTO([
            'paramDelete' => 'email',
            'valueDelete' => 'email@gmail.com2',
        ]);

        $this->repository
            ->delete($deleteUser);

        $dbh = $this->dbh;
        $sql = 'SELECT * FROM users;';
        $result = $dbh->query($sql);
        $result = $result->fetchAll(\PDO::FETCH_ASSOC);
        $resultData = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $this->assertEquals($expectedData, $resultData);
    }
    public function testDeleteByIdExceptions(): void
    {
        $deleteUser = new DeleteUserDTO([
            'paramDelete' => 'id',
            'valueDelete' => 3,
        ]);

        $this->expectException(EntityNotFoundException::class);

        $this->repository
            ->delete($deleteUser);
    }
    public function testDeleteEmailIdExceptions(): void
    {
        $deleteUser = new DeleteUserDTO([
            'paramDelete' => 'email',
            'valueDelete' => 'invalid',
        ]);

        $this->expectException(EntityNotFoundException::class);

        $this->repository
            ->delete($deleteUser);
    }
    private function createTestDatabase(): void
    {
        $credMySql = $this->credMySql;

        $dbh = new \PDO(
            "mysql:host={$credMySql['HOSTNAME']};",
            $credMySql['USERNAME'],
            $credMySql['PASSWORD']
        );

        $sql = 'CREATE DATABASE test;';
        $dbh->query($sql);
        $dbh->exec("use test;");
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
    }
    private function deleteTestDatabase(): void
    {
        $dbh = $this->dbh;
        $sql = 'DROP DATABASE test;';
        $dbh->query($sql);
    }
}
