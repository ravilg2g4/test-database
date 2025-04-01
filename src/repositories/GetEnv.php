<?php

declare(strict_types=1);
readonly class GetEnv
{
    public readonly string $dbSource;

    public function dbSource(): void
    {
        require_once '../vendor/autoload.php';

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $this->dbSource = $_ENV['DB_SOURCE'];
    }
}