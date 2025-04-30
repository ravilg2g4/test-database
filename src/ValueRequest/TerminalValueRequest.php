<?php

namespace App\ValueRequest;

use App\Exceptions\ChoiceActionException;
use App\Exceptions\DataForCRUDNotFoundException;
use App\Exceptions\DeleteUserException;
use App\Repositories\NewUser;

class TerminalValueRequest implements ValueRequestInterface
{
    public function getNewUser(): NewUser
    {
        $id = $this->getIdNewUser();
        $name = $this->getNameNewUser();
        $surname = $this->getSurnameNewUser();
        $email = $this->getEmailNewUser();
        return new NewUser(id: $id, name: $name, surname: $surname, email: $email);
    }
    public function getIdNewUser(): int
    {
        $idNewUser = new IdNewUser();
        return $idNewUser->idNewUser;
    }
    public function getNameNewUser(): string
    {
        $name = readline('Имя нового пользователя: ');
        $name = trim($name);
        if ($name === '') {
            throw new DataForCRUDNotFoundException(entityName: 'user', action: 'create', dataName: 'name');
        }
        return $name;
    }
    public function getSurnameNewUser(): string
    {
        $surname = readline('Фамилия нового пользователя: ');
        $surname = trim($surname);
        if ($surname === '') {
            throw new DataForCRUDNotFoundException(entityName: 'user', action: 'create', dataName: 'surname');
        }
        return $surname;
    }
    public function getEmailNewUser(): string
    {
        $email = readline('Почта нового пользователя: ');
        $email = trim($email);
        if ($email === '') {
            throw new DataForCRUDNotFoundException(entityName: 'user', action: 'create', dataName: 'name');
        }
        return $email;
    }
    public function getChoiceDelete(): string
    {
        $choiceDelete = readline("Вы знаете id или по почту пользователя? (id/email): ");
        $choiceDelete = trim($choiceDelete);
        if ($choiceDelete !== 'id' && $choiceDelete !== 'email') {
            throw new ChoiceActionException(action: 'параметр для удаления пользователя', actualChoice: $choiceDelete, expectedChoice: 'id или email');
        }
        return $choiceDelete;
    }
    public function getValueDelete(string $choiceDelete): string|int
    {
        $valueDelete = readline("Значение {$choiceDelete} для удаления пользователя: ");
        $valueDelete = trim($valueDelete);
        if ($valueDelete === '') {
            throw new DataForCRUDNotFoundException(entityName: 'user', action: 'delete', dataName: 'valueDelete');
        }
        if ($choiceDelete === 'id') {
            $valueDelete = (int)$valueDelete;
        }
        if ($valueDelete === 0) {
            throw new DeleteUserException(message: 'Введен некорректный id пользователя');
        }
        return $valueDelete;
    }
}
