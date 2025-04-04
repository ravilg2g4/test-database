<?php

declare(strict_types=1);

namespace App\repositories;

readonly class GetEnv
{
    public string $dbSource;
    public array $credMySql;

    public function getDbSource(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->load();

        $this->dbSource = $_ENV['DB_SOURCE'];
    }
    public function getCredMySql(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->load();

        $this->credMySql = [
            'HOSTNAME' => $_ENV['DB_HOSTNAME'],
            'USERNAME' => $_ENV['DB_USERNAME'],
            'PASSWORD' => $_ENV['DB_PASSWORD'],
            'DATABASE' => $_ENV['DB_DATABASE'],
            'PORT' => (int)$_ENV['DB_PORT'],
        ];
    }
}