<?php

namespace App;

use App\Exceptions\ChoiceActionException;

class ChoiceCRUD
{
    public string $choiceCRUD;
    public function __construct()
    {
        if (array_key_exists("REQUEST_URI", $_SERVER) === true) {
            $this->choiceCRUD = $this->getChoiceCRUDHttp();
        } elseif (array_key_exists("REQUEST_URI", $_SERVER) === false) {
            $this->choiceCRUD = $this->getChoiceCRUDTerminal();
        }
    }
    public function getChoiceCRUDTerminal(): string
    {
        $textForChoiceAction = <<<EOT
Что Вы хотите сделать с базой данных о пользователях?
Выберите номер действия:
1. Показать список пользователей;
2. Добавить пользователя;
3. Удалить пользователя.

EOT;
        echo $textForChoiceAction;
        $action = readline('Ваш выбор: ');
        $action = trim($action);
        $result = match ($action) {
            '1' => 'read',
            '2' => 'create',
            '3' => 'delete',
            default => throw new ChoiceActionException(action: 'действие с базой данных', actualChoice: $action, expectedChoice: '1, 2 или 3')
        };
        return $result;
    }
    public function getChoiceCRUDHttp(): string
    {
        $result = match ($_SERVER['REQUEST_METHOD']) {
            'GET' => 'read',
            'POST' => 'create',
            'DELETE' => 'delete',
            default => throw new ChoiceActionException(action: 'действие с базой данных', actualChoice: $_SERVER['REQUEST_METHOD'], expectedChoice: 'GET, POST, DELETE')
        };
        return $result;
    }
}
