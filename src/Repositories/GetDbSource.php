<?php

namespace App\Repositories;

class GetDbSource
{
    public string $dbSource;
    private string $correctDbSource = 'json, mysql';
    private function checkDbSource(): void
    {
        GetEnv::getEnv();
        $check = true;

        if (array_key_exists('DB_SOURCE', $_ENV) === false) {
            echo "Не найдено значение DB_SOURCE\nВнесите его в файл .env\n";
            $check = false;
        } elseif (str_contains($this->correctDbSource, $_ENV['DB_SOURCE']) === false) {
            echo "Значение DB_SOURCE указано некорректно\nЗапишите правильное значение в .env\n";
            $check = false;
        }

        if ($check === false) {
            exit();
        }
    }
    public function getDbSource(): void
    {
        GetEnv::getEnv();
        $this->checkDbSource();
        $this->dbSource = $_ENV['DB_SOURCE'];
    }
}