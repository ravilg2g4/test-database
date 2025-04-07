<?php

declare(strict_types=1);

namespace App\Repositories;

interface RepositoryInterface
{
    public function read(): void;
    public function write(): void;
    public function deleteId(): void;
    public function deleteEmail(): void;
}