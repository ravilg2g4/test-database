<?php

declare(strict_types=1);

namespace App\Repositories\MySql;

use App\EnvProvider;
use App\Exceptions\EntityNotFoundException;

class CredMySql
{
    public array $credMySql;
    private array $parametersCredMySql = ['DB_HOSTNAME', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE'];
    public function __construct()
    {
        EnvProvider::getEnv();
        $this->checkCredMySql();

        $this->credMySql = [
            'HOSTNAME' => $_ENV['DB_HOSTNAME'],
            'USERNAME' => $_ENV['DB_USERNAME'],
            'PASSWORD' => $_ENV['DB_PASSWORD'],
            'DATABASE' => $_ENV['DB_DATABASE']
        ];
    }
    private function checkCredMySql(): void
    {
        foreach ($this->parametersCredMySql as $key) {
            if (array_key_exists($key, $_ENV) === false || $_ENV[$key] === '') {
                throw new EntityNotFoundException(entityName: 'credMySql', failed: $key);
            }
        }
    }
}
