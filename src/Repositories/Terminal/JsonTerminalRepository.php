<?php

declare(strict_types=1);

namespace App\Repositories\Terminal;

use App\Repositories\RepositoryInterface;

class JsonTerminalRepository implements RepositoryInterface
{
    private string $choiceDelete;
    private string $valueDelete;
    private array $newUser;
    private function getJsonArray(): array
    {
        $json = @file_get_contents(__DIR__ . '/../database.json');

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
        $this->getNewUser();
        $newUser = $this->newUser;
        $dataBase[] = $newUser;

        file_put_contents(__DIR__ . '/../database.json', json_encode($dataBase, JSON_PRETTY_PRINT));
    }
    private function getIdNewUser(): int
    {
        $dataBase = $this->getJsonArray();
        $dataBase = $dataBase[array_key_last($dataBase)];
        $id = $dataBase['id'] + 1;
        return $id;
    }
    private function getNewUser(): void
    {
        $name = readline('Имя нового пользователя: ');
        $surname = readline('Фамилия нового пользователя: ');
        $email = readline('Почта нового пользователя: ');

        $name = trim($name);
        $surname = trim($surname);
        $email = trim($email);

        $newUser = ['name' => $name, 'surname' => $surname, 'email' => $email];
        $id = $this->getIdNewUser();
        $id = ['id' => $id];
        $newUser = array_merge($id, $newUser);
        $this->newUser = $newUser;
    }
    private function checkCreate(): bool
    {
        $dataBase = $this->getJsonArray();
        $lastUser = $dataBase[array_key_last($dataBase)];
        $newUser = $this->newUser;
        if ($newUser === $lastUser) {
            return true;
        } else {
            return false;
        }
    }
    public function answerCreate(): void
    {
        $checkCreate = $this->checkCreate();
        if ($checkCreate === true) {
            $answer = 'Пользователь добавлен в базу данных' . PHP_EOL;
        } elseif ($checkCreate === false) {
            $answer = 'Пользователь не добавлен в базу данных' . PHP_EOL;
        }
        echo $answer;
    }
    public function delete(): void
    {
        $choiceDelete = $this->choiceDelete();
        if ($choiceDelete === 'id') {
            $this->deleteId();
        } elseif ($choiceDelete === 'email') {
            $this->deleteEmail();
        }
    }
    private function choiceDelete(): string
    {
        $choiceDelete = readline('Вы знаете id или по почту? (id/email): ');
        $choiceDelete = trim($choiceDelete);
        $this->choiceDelete = $choiceDelete;
        return $choiceDelete;
    }
    public function deleteId(): void
    {
        $dataBase = $this->getJsonArray();

        $id = readline('Введите id пользователя, которого хотите удалить: ');
        $id = trim($id);
        $this->valueDelete = $id;

        unset($dataBase[$id]);

        file_put_contents(__DIR__ . '/../database.json', json_encode($dataBase, JSON_PRETTY_PRINT));
    }

    public function deleteEmail(): void
    {
        $dataBase = $this->getJsonArray();

        $email = readline('Введите почту пользователя: ');
        $email = trim($email);
        $this->valueDelete = $email;

        $index = array_search($email, array_column($dataBase, 'email', 'id'));
        unset($dataBase[$index]);

        file_put_contents(__DIR__ . '/../database.json', json_encode($dataBase, JSON_PRETTY_PRINT));
    }
    private function checkDelete(): bool
    {
        $dataBase = $this->getJsonArray();
        $choiceDelete = $this->choiceDelete;
        $valueDelete = $this->valueDelete;
        if ($choiceDelete === 'id') {
            $checkValue = @$dataBase[$valueDelete];
        } elseif ($choiceDelete === 'email') {
            $checkValue = array_search($valueDelete, array_column($dataBase, 'email', 'id'));
        }
        $check = empty($checkValue);
        return $check;
    }
    public function answerDelete(): void
    {
        $checkDelete = $this->checkDelete();
        if ($checkDelete === true) {
            $answer = 'Пользователь успешно удален из базы данных' . PHP_EOL;
        } else {
            $answer = 'Пользователь не был удален из базы данных' . PHP_EOL;
        }
        print_r($answer);
    }
}
