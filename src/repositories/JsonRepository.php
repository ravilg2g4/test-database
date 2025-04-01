<?php

declare(strict_types=1);

namespace repositories;


class JsonRepository implements RepositoryInterface
{
    public function read(): void
    {
        $json = file_get_contents('database.json');
        $dataBase = json_decode($json, true);

        print_r($dataBase);
    }

    public function write(): void
    {
        $name = readline('Имя нового пользователя: ');
        $surname = readline('Фамилия нового пользователя: ');
        $email = readline('Почта нового пользователя: ');

        $name = trim($name);
        $surname = trim($surname);
        $email = trim($email);

        $json = file_get_contents('database.json');
        $dataBase = json_decode($json, true);
        $id = 'id_' . array_key_last($dataBase) + 1;

        $newUser = ['name' => $name, 'surname' => $surname, 'email' => $email, 'id' => $id];
        $json = file_get_contents('database.json');
        $dataBase = json_decode($json, true);
        $dataBase[] = $newUser;

        file_put_contents('database.json', json_encode($dataBase, JSON_PRETTY_PRINT));
    }

    public function deleteId(): void
    {
        $json = file_get_contents('database.json');
        $dataBase = json_decode($json, true);

        $id = readline('Введите id пользователя, которого хотите удалить: ');
        $id = trim($id);

        unset($dataBase[$id]);

        file_put_contents('database.json', json_encode($dataBase, JSON_PRETTY_PRINT));
    }

    public function deleteEmail(): void
    {
        $json = file_get_contents('database.json');
        $dataBase = json_decode($json, true);

        $email = readline('Введите почту пользователя: ');
        $email = trim($email);

        $index = array_search($email, array_column($dataBase, 'email', 'id'));
        $index = substr($index, 3);
        unset($dataBase[$index]);

        file_put_contents('database.json', json_encode($dataBase, JSON_PRETTY_PRINT));
    }
}