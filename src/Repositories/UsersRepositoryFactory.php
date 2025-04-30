<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DatabaseSourceUsersProvider;

class UsersRepositoryFactory
{
    public function getUsersRepository(): RepositoryInterface
    {
        $databaseSourceUsers = new DatabaseSourceUsersProvider();
        $databaseSourceUsers = $databaseSourceUsers->databaseSourceUsers;
        if ($databaseSourceUsers === 'json') {
            return new JsonRepository();
        } elseif ($databaseSourceUsers === 'mysql') {
            return new MySqlRepository();
        }
    }
}
