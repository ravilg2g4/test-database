<?php

namespace App\Repositories;

class GetCredMySql
{
    public array $credMySql;
    private array $parametersCredMySql = ['DB_HOSTNAME', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE', 'DB_PORT'];
    private function checkCredMySql(): void
    {
        GetEnv::getEnv();
        $check = true;

        foreach ($this->parametersCredMySql as $key) {
            if (array_key_exists($key, $_ENV) === false) {
                echo "Не найдено значение {$key}\nВнесите его в файл .env\n";
                $check = false;
            }
        }
        if ($check === false) {
            exit();
        }
    }
    public function getCredMySql(): void
    {
        GetEnv::getEnv();
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
