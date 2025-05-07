<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\DeleteUserDTO;
use App\DTO\NewUserDTO;

interface RepositoryInterface
{
    public function read(): void;
    public function create(NewUserDTO $newUser): void;
    public function getNextId(): int;
    public function delete(DeleteUserDTO $deleteUser): void;
}
