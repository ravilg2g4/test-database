<?php

declare(strict_types=1);

namespace App\Repositories;

use App\EnvProvider;
use App\Exceptions\EntityNotFoundException;

class CredMySqlProvider
{
    public array $credMySql;
    private array $parametersCredMySql = ['DB_HOSTNAME', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE', 'DB_PORT'];
    private function checkCredMySql(): void
    {
        EnvProvider::getEnv();

        foreach ($this->parametersCredMySql as $key) {
            if (array_key_exists($key, $_ENV) === false || $_ENV[$key] === '') {
                throw new EntityNotFoundException(entityName: 'credMySql', failed: $key);
            }
        }
    }
    public function getCredMySql(): void
    {
        EnvProvider::getEnv();
        $this->checkCredMySql();

        $this->credMySql = [
            'HOSTNAME' => $_ENV['DB_HOSTNAME'],
            'USERNAME' => $_ENV['DB_USERNAME'],
            'PASSWORD' => $_ENV['DB_PASSWORD'],
            'DATABASE' => $_ENV['DB_DATABASE'],
            'PORT' => (int)$_ENV['DB_PORT'],
        ];
    }
}
