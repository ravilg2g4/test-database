<?php

declare(strict_types=1);

namespace App\CrudOperationProvider;

use App\Exceptions\ChoiceActionException;

class CrudOperationProviderFromTerminal
{
    public function getCrudOperation(): string
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
        $crudOperation = match ($action) {
            '1' => 'read',
            '2' => 'create',
            '3' => 'delete',
            default => throw new ChoiceActionException(action: 'действие с базой данных', actualChoice: $action, expectedChoice: '1, 2 или 3')
        };
        return $crudOperation;
    }
}
