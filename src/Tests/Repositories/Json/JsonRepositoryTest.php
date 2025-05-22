<?php

namespace App\Tests\Repositories\Json;

use App\DTO\DeleteUserDTO;
use App\DTO\NewUserDTO;
use App\Exceptions\EntityNotFoundException;
use App\Repositories\Json\JsonRepository;
use PHPUnit\Framework\TestCase;

class JsonRepositoryTest extends TestCase
{
    private string $testDatabasePath = '/var/www/html/src/Tests/Repositories/Json/test_database.json';
    private JsonRepository $repository;
    protected function setUp(): void
    {
        $data = [[
            "id" => 1,
            "name" => "name1",
            "surname" => "surname1",
            "email" => "email1@gmail.com"
        ], [
            "id" => 2,
            "name" => "name2",
            "surname" => "surname2",
            "email" => "email2@gmail.com"
        ]];
        $data = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents(filename: $this->testDatabasePath, data: $data);
        $this->repository = new JsonRepository($this->testDatabasePath);
    }
    protected function tearDown(): void
    {
        unlink(filename: $this->testDatabasePath);
    }
    public function testRead(): void
    {
        $dataForCheck = [[
            "id" => 1,
            "name" => "name1",
            "surname" => "surname1",
            "email" => "email1@gmail.com"
        ], [
            "id" => 2,
            "name" => "name2",
            "surname" => "surname2",
            "email" => "email2@gmail.com"
        ]];
        $dataForCheck = json_encode($dataForCheck, JSON_PRETTY_PRINT);

        $this->repository
            ->read();

        $this->expectOutputString($dataForCheck);
    }
    public function testCreate(): void
    {
        $expectedData = [[
            "id" => 1,
            "name" => "name1",
            "surname" => "surname1",
            "email" => "email1@gmail.com"
        ], [
            "id" => 2,
            "name" => "name2",
            "surname" => "surname2",
            "email" => "email2@gmail.com"
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

        $resultData = file_get_contents(filename: $this->testDatabasePath);
        $this->assertEquals($expectedData, $resultData);
    }
    public function testDeleteById(): void
    {
        $expectedData = [[
            "id" => 1,
            "name" => "name1",
            "surname" => "surname1",
            "email" => "email1@gmail.com"
        ]];
        $expectedData = json_encode($expectedData, JSON_PRETTY_PRINT);
        $deleteUser = new DeleteUserDTO([
            'paramDelete' => 'id',
            'valueDelete' => 2,
        ]);

        $this->repository
            ->delete($deleteUser);

        $resultData = file_get_contents(filename: $this->testDatabasePath);
        $this->assertEquals($expectedData, $resultData);
    }
    public function testDeleteByEmail(): void
    {
        $expectedData = [[
            "id" => 1,
            "name" => "name1",
            "surname" => "surname1",
            "email" => "email1@gmail.com"
        ]];
        $expectedData = json_encode($expectedData, JSON_PRETTY_PRINT);
        $deleteUser = new DeleteUserDTO([
            'paramDelete' => 'email',
            'valueDelete' => 'email2@gmail.com',
        ]);

        $this->repository
            ->delete($deleteUser);

        $resultData = file_get_contents(filename: $this->testDatabasePath);
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
}
