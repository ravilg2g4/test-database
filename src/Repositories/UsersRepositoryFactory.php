<?php

declare(strict_types=1);

namespace App\Repositories;

class UsersRepositoryFactory
{
    public static function getUsersRepository(string $dbSource): ?RepositoryInterface
    {
        if ($dbSource === 'mysql') {
            return new MySqlRepository();
        } elseif ($dbSource === 'json') {
            return new JsonRepository();
        } else {
            return null;
        }
    }
}
