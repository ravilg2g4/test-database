<?php

declare(strict_types=1);

namespace App\Repositories;

interface RepositoryInterface
{
    public function read(): void;
    public function create(NewUser $newUser): void;
    public function delete(string $choiceDelete, string $valueDelete): void;
}
