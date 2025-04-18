<?php

declare(strict_types=1);

namespace App\Repositories;

interface RepositoryInterface
{
    public function read(): void;
    public function create(): void;
    public function delete(): void;
}
