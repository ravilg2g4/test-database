<?php

declare(strict_types=1);

namespace App\Tests;

use App\DatabaseSourceUsers;
use App\Exceptions\ChoiceRepositoryException;
use PHPUnit\Framework\TestCase;

class DatabaseSourceUsersTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($_ENV['DB_SOURCE']);
    }
    public function testDatabaseSourceUsersForMySql(): void
    {
        $expectedDbSource = 'mysql';
        $_ENV['DB_SOURCE'] = $expectedDbSource;
        $databaseSourceUsers = new DatabaseSourceUsers();

        $result = $databaseSourceUsers->databaseSourceUsers;

        $this->assertEquals($expectedDbSource, $result);
    }
    public function testDatabaseSourceUsersForJson(): void
    {
        $expectedDbSource = 'json';
        $_ENV['DB_SOURCE'] = $expectedDbSource;
        $databaseSourceUsers = new DatabaseSourceUsers();

        $result = $databaseSourceUsers->databaseSourceUsers;

        $this->assertEquals($expectedDbSource, $result);
    }
    public function testInvalidDatabaseSourceUsers(): void
    {
        $_ENV['DB_SOURCE'] = 'invalid';

        $this->expectException(ChoiceRepositoryException::class);

        $databaseSourceUsers = new DatabaseSourceUsers();
    }
}
