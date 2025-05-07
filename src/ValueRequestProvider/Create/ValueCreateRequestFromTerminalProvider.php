<?php

declare(strict_types=1);

namespace App\ValueRequestProvider\Create;

use App\Exceptions\DataForCRUDNotFoundException;

class ValueCreateRequestFromTerminalProvider implements ValueCreateRequestProviderInterface
{
    public function getArrayValueRequest(): array
    {
        $name = $this->getNameNewUser();
        $surname = $this->getSurnameNewUser();
        $email = $this->getEmailNewUser();
        return ['name' => $name, 'surname' => $surname, 'email' => $email];
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
}
