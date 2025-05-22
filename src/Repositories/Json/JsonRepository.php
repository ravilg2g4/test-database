<?php

declare(strict_types=1);

namespace App\Repositories\Json;

use App\DTO\DeleteUserDTO;
use App\DTO\NewUserDTO;
use App\Exceptions\CreateNewUserException;
use App\Exceptions\DataForCRUDNotFoundException;
use App\Exceptions\DeleteUserException;
use App\Exceptions\EntityNotFoundException;
use App\Repositories\RepositoryInterface;

class JsonRepository implements RepositoryInterface
{
    private string $path;
    public function __construct(string $path)
    {
        $this->path = $path;
    }
    public function getDatabaseArray(): array
    {
        $databaseJson = @file_get_contents($this->path);

        if ($databaseJson === false) {
            throw new EntityNotFoundException(entityName: 'база данных в формате json', failed: 'path', fieldValue: $this->path);
        }
        return json_decode($databaseJson, true);
    }
    public function read(): void
    {
        $databaseArray = $this->getDatabaseArray();
        $databaseJson = json_encode($databaseArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        print_r($databaseJson);
    }
    public function create(NewUserDTO $newUser): void
    {
        $databaseArray = $this->getDatabaseArray();
        $newUser = ['id' => $this->getNextId(), 'name' => $newUser->name, 'surname' => $newUser->surname, 'email' => $newUser->email];
        $databaseArray[] = $newUser;
        $this->saveAfterCreate($databaseArray);
    }
    private function saveAfterCreate(array $newDatabaseArray): void
    {
        $create = @file_put_contents($this->path, json_encode($newDatabaseArray, JSON_PRETTY_PRINT));
        if ($create === false) {
            throw new CreateNewUserException('Ошибка при сохранении базы данных формата json после добавления нового пользователя');
        }
    }
    public function getNextId(): int
    {
        $databaseArray = $this->getDatabaseArray();
        $lastUser = $databaseArray[array_key_last($databaseArray)];
        if (is_null($lastUser)) {
            throw new DataForCRUDNotFoundException(entityName: 'user', action: 'create', dataName: 'id');
        }
        return $lastUser['id'] + 1;
    }
    public function delete(DeleteUserDTO $deleteUser): void
    {
        $paramDelete = $deleteUser->paramDelete;
        $valueDelete = $deleteUser->valueDelete;
        if ($paramDelete === 'id') {
            $newDatabaseArray = $this->deleteById($valueDelete);
        } elseif ($paramDelete === 'email') {
            $newDatabaseArray = $this->deleteByEmail($valueDelete);
        }
        $this->checkDelete($newDatabaseArray, $paramDelete, $valueDelete);
        $this->saveAfterDelete($newDatabaseArray);
    }
    private function deleteById(int $id): array
    {
        $databaseArray = $this->getDatabaseArray();
        $index = array_search($id, array_column($databaseArray, 'id', 'id')) - 1;
        unset($databaseArray[$index]);
        return $databaseArray;
    }
    private function deleteByEmail(string $email): array
    {
        $databaseArray = $this->getDatabaseArray();
        $index = array_search($email, array_column($databaseArray, 'email', 'id')) - 1;
        unset($databaseArray[$index]);
        return $databaseArray;
    }
    private function checkDelete(array $newDatabaseArray, string $paramDelete, string|int $valueDelete): void
    {
        $databaseArray = $this->getDatabaseArray();
        if ($databaseArray === $newDatabaseArray) {
            throw new EntityNotFoundException(entityName: 'user', failed: $paramDelete, fieldValue: $valueDelete);
        }
    }
    private function saveAfterDelete(array $newDatabaseArray): void
    {
        $delete = @file_put_contents($this->path, json_encode($newDatabaseArray, JSON_PRETTY_PRINT));
        if ($delete === false) {
            throw new DeleteUserException(message: 'Ошибка при сохранении базы данных формата json после удаления пользователя');
        }
    }
}
