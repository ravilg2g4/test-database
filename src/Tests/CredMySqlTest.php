<?php

declare(strict_types=1);

namespace App\Tests;

use App\Exceptions\EntityNotFoundException;
use App\Repositories\MySql\CredMySql;
use PHPUnit\Framework\TestCase;

class CredMySqlTest extends TestCase
{
    protected function tearDown(): void
    {
        unset(
            $_ENV['DB_HOSTNAME'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD'],
            $_ENV['DB_DATABASE']
        );
    }
    public function testCredMySql(): void
    {
        $_ENV['DB_HOSTNAME'] = 'localhost';
        $_ENV['DB_USERNAME'] = 'user';
        $_ENV['DB_PASSWORD'] = 'password';
        $_ENV['DB_DATABASE'] = 'database';
        $expectedCredMySql = [
            'HOSTNAME' => 'localhost',
            'USERNAME' => 'user',
            'PASSWORD' => 'password',
            'DATABASE' => 'database',
            ];
        $credMySql = new CredMySql();

        $result = $credMySql->credMySql;

        $this->assertEquals($expectedCredMySql, $result);
    }
    public function testCredMySqlForEmptyCred(): void
    {
        $_ENV['DB_HOSTNAME'] = '';
        $_ENV['DB_USERNAME'] = 'user';
        $_ENV['DB_PASSWORD'] = 'password';
        $_ENV['DB_DATABASE'] = 'database';
        $this->expectException(EntityNotFoundException::class);

        $credMySql = new CredMySql();
    }
}
