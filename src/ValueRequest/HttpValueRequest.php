<?php

namespace App\ValueRequest;

use App\Exceptions\ChoiceActionException;
use App\Exceptions\DataForCRUDNotFoundException;
use App\Repositories\NewUser;

class HttpValueRequest implements ValueRequestInterface
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
    public function getArrayFromJsonPost(): array
    {
        $json = file_get_contents('php://input');
        if ($json === false) {
            throw new DataForCRUDNotFoundException(entityName: 'user', action: 'create', dataName: 'json из POST-запроса');
        }
        return json_decode($json, true);
    }
    public function getNameNewUser(): string
    {
        $arrayNewUser = $this->getArrayFromJsonPost();
        $name = $arrayNewUser['name'];
        if ($name === '' or $name === null) {
            throw new DataForCRUDNotFoundException(entityName: 'user', action: 'create', dataName: 'name');
        }
        return $name;
    }
    public function getSurnameNewUser(): string
    {
        $arrayNewUser = $this->getArrayFromJsonPost();
        $surname = $arrayNewUser['surname'];
        if ($surname === '' or $surname === null) {
            throw new DataForCRUDNotFoundException(entityName: 'user', action: 'create', dataName: 'surname');
        }
        return $surname;
    }
    public function getEmailNewUser(): string
    {
        $arrayNewUser = $this->getArrayFromJsonPost();
        $email = $arrayNewUser['email'];
        if ($email === '' or $email === null) {
            throw new DataForCRUDNotFoundException(entityName: 'user', action: 'create', dataName: 'email');
        }
        return $email;
    }
    public function getChoiceDelete(): string
    {
        $request = $_SERVER['REQUEST_URI'];
        $requestArray = explode('/', $request);
        $choiceDelete = $requestArray[count($requestArray) - 2];
        if (($choiceDelete !== 'id' && $choiceDelete !== 'email') || is_null($choiceDelete)) {
            throw new ChoiceActionException(action: 'параметр для удаления пользователя', actualChoice: $choiceDelete, expectedChoice: 'id или email');
        }
        return $choiceDelete;
    }
    public function getValueDelete(string $choiceDelete): string|int
    {
        $request = $_SERVER['REQUEST_URI'];
        $requestArray = explode('/', $request);
        $valueDelete = $requestArray[count($requestArray) - 1];
        if (is_null($valueDelete) || $valueDelete === '') {
            throw new DataForCRUDNotFoundException(entityName: 'user', action: 'delete', dataName: 'valueDelete');
        }
        if ($choiceDelete === 'id') {
            $valueDelete = (int)$valueDelete;
        }
        return $valueDelete;
    }
}
