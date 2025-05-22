<?php

declare(strict_types=1);

namespace App\Tests\Repositories;

use App\Repositories\Json\JsonRepository;
use App\Repositories\MySql\DbhMySqlProvider;
use App\Repositories\MySql\MySqlRepository;
use App\Repositories\UsersRepositoryFactory;
use PHPUnit\Framework\TestCase;

class UsersRepositoryFactoryTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($_ENV['DB_SOURCE']);
    }
    public function testGetUsersRepositoryForDbJson(): void
    {
        $_ENV['DB_SOURCE'] = 'json';
        $_ENV['JSON_DATABASE_PATH'] = 'path';
        $expectedUserRepository = new JsonRepository('path');

        $usersRepositoryFactory = new UsersRepositoryFactory();
        $actualUserRepository = $usersRepositoryFactory->getUsersRepository();

        $this->assertEquals($expectedUserRepository, $actualUserRepository);
        unset($_ENV['JSON_DATABASE_PATH']);
    }
    public function testGetUsersRepositoryForDbMySql(): void
    {
        $dbhProvider = new DbhMySqlProvider();
        $dbh = $dbhProvider->getDbhMySql();
        $expectedUserRepository = new MySqlRepository($dbh);
        $_ENV['DB_SOURCE']  = 'mysql';

        $usersRepositoryFactory = new UsersRepositoryFactory();
        $actualUserRepository = $usersRepositoryFactory->getUsersRepository();

        $this->assertEquals($expectedUserRepository, $actualUserRepository);
    }
}
