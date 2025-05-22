<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DatabaseSourceUsers;
use App\Repositories\Json\JsonRepository;
use App\Repositories\MySql\DbhMySqlProvider;
use App\Repositories\MySql\MySqlRepository;
use App\Repositories\Json\JsonDatabasePath;

class UsersRepositoryFactory
{
    public static function getUsersRepository(): RepositoryInterface
    {
        $databaseSourceUsers = new DatabaseSourceUsers();
        $databaseSourceUsers = $databaseSourceUsers->databaseSourceUsers;
        if ($databaseSourceUsers === 'json') {
            $jsonPath = new JsonDatabasePath();
            $jsonPath = $jsonPath->jsonDatabasePath;
            return new JsonRepository($jsonPath);
        } elseif ($databaseSourceUsers === 'mysql') {
            $dbhMySqlProvider = new DbhMySqlProvider();
            $dbhMySql = $dbhMySqlProvider->getDbhMySql();
            return new MySqlRepository($dbhMySql);
        }
    }
}
