<?php

declare(strict_types=1);

namespace App\Repositories\Http;

use App\Repositories\RepositoryInterface;

class JsonHttpRepository implements RepositoryInterface
{
    private function getJsonArray(): array
    {
        $json = @file_get_contents(__DIR__ . '/database.json');

        if ($json === false) {
            echo "Json-файл для хранения данных о пользователях не найден\n";
            exit();
        }

        $dataBase = json_decode($json, true);

        return $dataBase;
    }
    public function read(): void
    {
        $dataBase = $this->getJsonArray();
        print_r($dataBase);
    }

    public function create(): void
    {
        $dataBase = $this->getJsonArray();

        $name = readline('Имя нового пользователя: ');
        $surname = readline('Фамилия нового пользователя: ');
        $email = readline('Почта нового пользователя: ');

        $name = trim($name);
        $surname = trim($surname);
        $email = trim($email);

        $id = 'id_' . array_key_last($dataBase) + 1;

        $newUser = ['name' => $name, 'surname' => $surname, 'email' => $email, 'id' => $id];
        $json = file_get_contents('database.json');
        $dataBase = json_decode($json, true);
        $dataBase[] = $newUser;

        file_put_contents(__DIR__ . '/database.json', json_encode($dataBase, JSON_PRETTY_PRINT));
    }

    public function delete(): void
    {
        $dataBase = $this->getJsonArray();

        $id = readline('Введите id пользователя, которого хотите удалить: ');
        $id = trim($id);

        unset($dataBase[$id]);

        file_put_contents(__DIR__ . '/database.json', json_encode($dataBase, JSON_PRETTY_PRINT));
    }

    public function deleteByEmail(): void
    {
        $dataBase = $this->getJsonArray();

        $email = readline('Введите почту пользователя: ');
        $email = trim($email);

        $index = array_search($email, array_column($dataBase, 'email', 'id'));
        $index = substr($index, 3);
        unset($dataBase[$index]);

        file_put_contents(__DIR__ . '/database.json', json_encode($dataBase, JSON_PRETTY_PRINT));
    }
}
